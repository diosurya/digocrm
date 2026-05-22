<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SalesOrderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Order::with(['customer', 'account']);

        if ($user->role === 'superadmin') {
            if ($request->filled('account_id')) {
                $query->where('account_id', $request->account_id);
            }
        } elseif ($user->role === 'manager_marketing') {
            $assignedAccountIds = $user->accounts()->pluck('accounts.id')->toArray();
            $query->whereIn('account_id', $assignedAccountIds);
        } else {
            $assignedAccountIds = $user->accounts()->pluck('accounts.id')->toArray();
            $query->whereIn('account_id', $assignedAccountIds)
                  ->whereHas('customer', function($q) use ($user) {
                      $q->where('user_id', $user->id);
                  });
        }

        $orders = $query->latest()->paginate(15);
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $user = auth()->user();
        $accounts = $user->role === 'superadmin' ? \App\Models\Account::all() : $user->accounts;
        $customers = Customer::whereIn('account_id', $accounts->pluck('id'))->get();
        $productsByAccount = Product::all()->groupBy('account_id');

        return view('orders.create', compact('accounts', 'customers', 'productsByAccount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|uuid|exists:customers,id',
            'order_date' => 'required|date',
            'payment_term' => 'required|string',
            'currency' => 'required|string|max:10',
            'dp_amount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|uuid|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.tax_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $customer = Customer::find($validated['customer_id']);
            
            $order = Order::create([
                'order_number' => 'SO-' . date('ym') . strtoupper(Str::random(5)),
                'customer_id' => $customer->id,
                'account_id' => $customer->account_id,
                'status' => 'draft',
                'order_date' => $validated['order_date'],
                'payment_term' => $validated['payment_term'],
                'currency' => $validated['currency'],
                'dp_amount' => $validated['dp_amount'] ?? 0,
                'erp_sync_status' => 'pending',
            ]);

            $totalAmount = 0;
            $totalTax = 0;
            $totalDiscount = 0;

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                $baseSubtotal = $product->price * $item['quantity'];
                
                $discount = $item['discount'] ?? 0;
                $taxPercent = $item['tax_percent'] ?? 0;
                
                $afterDiscount = $baseSubtotal - $discount;
                $taxAmount = ($afterDiscount * $taxPercent) / 100;
                $finalSubtotal = $afterDiscount + $taxAmount;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $finalSubtotal,
                ]);

                $totalAmount += $finalSubtotal;
                $totalTax += $taxAmount;
                $totalDiscount += $discount;
            }

            $remaining = $totalAmount - $order->dp_amount;

            $order->update([
                'total_amount' => $totalAmount,
                'tax_amount' => $totalTax,
                'discount_amount' => $totalDiscount,
                'remaining_balance' => $remaining > 0 ? $remaining : 0,
            ]);

            return redirect()->route('orders.index')->with('success', 'Sales Order created successfully.');
        });
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'account', 'items.product']);
        return view('orders.show', compact('order'));
    }
}
