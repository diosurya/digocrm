<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Account;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Product::with('account');

        // RBAC Scoping
        if ($user->role !== 'superadmin') {
            $assignedAccountIds = $user->accounts()->pluck('accounts.id')->toArray();
            $query->whereIn('account_id', $assignedAccountIds);
        } elseif ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }

        $products = $query->latest()->paginate(25)->withQueryString();
        $accounts = Account::all();
        
        return view('products.index', compact('products', 'accounts'));
    }

    public function create()
    {
        $accounts = Account::all();
        return view('products.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'account_id' => $user->isSuperadmin() ? 'required|uuid|exists:accounts,id' : 'nullable',
            'sku' => 'required|string|unique:products,sku',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        if (!$user->isSuperadmin()) {
            $validated['account_id'] = $user->account_id;
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        if (!auth()->user()->isSuperadmin() && $product->account_id !== auth()->user()->account_id) {
            abort(403);
        }
        $accounts = Account::all();
        return view('products.edit', compact('product', 'accounts'));
    }

    public function update(Request $request, Product $product)
    {
        if (!auth()->user()->isSuperadmin() && $product->account_id !== auth()->user()->account_id) {
            abort(403);
        }

        $validated = $request->validate([
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if (!auth()->user()->isSuperadmin() && $product->account_id !== auth()->user()->account_id) {
            abort(403);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
