<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Account;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;
use App\Imports\CustomersImport;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Customer::with(['account', 'user']);

        // Filter based on role and assigned accounts
        if ($user->role !== 'superadmin') {
            $assignedAccountIds = $user->accounts()->pluck('accounts.id')->toArray();
            $query->whereIn('account_id', $assignedAccountIds);

            // If marketing, only see their own customers
            if ($user->isMarketing()) {
                $query->where('user_id', $user->id);
            }
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('company_name', 'like', "%$s%")
                  ->orWhere('customer_code', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%");
            });
        }

        $customers = $query->latest()->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $user = auth()->user();
        $accounts = $user->role === 'superadmin' ? Account::all() : $user->accounts;
        
        // Group products by account for dynamic filtering in frontend
        $products = Product::whereIn('account_id', $accounts->pluck('id'))->get()->groupBy('account_id');
        
        return view('customers.create', compact('accounts', 'products'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'account_id' => $user->role === 'superadmin' ? 'required|uuid|exists:accounts,id' : 'nullable',
            'user_id' => 'nullable|uuid|exists:users,id',
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'type' => 'required|in:individual,corporate',
            'status' => 'required|in:ACTIVE,INACTIVE,BLACKLIST,ARCHIVED',
            'whatsapp' => 'nullable|string|max:20',
            'alt_phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:customers,email',
            'location' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'source' => 'nullable|string|max:255',
            'source_reference' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high',
            'payment_term' => 'nullable|string|max:100',
            'currency' => 'nullable|string|max:10',
            'tax_type' => 'nullable|string|max:50',
            'npwp' => 'nullable|string|max:50',
            'follow_up_date' => 'nullable|date',
            'last_contact_date' => 'nullable|date',
            'next_action' => 'nullable|string|max:255',
            'important_chat' => 'nullable|string',
        ]);

        // Auto-assign account for non-superadmins
        if ($user->role === 'superadmin') {
            $validated['account_id'] = $request->account_id;
        } else {
            $assignedAccountIds = $user->accounts()->pluck('accounts.id')->toArray();
            if (in_array($request->account_id, $assignedAccountIds)) {
                $validated['account_id'] = $request->account_id;
            } else {
                $validated['account_id'] = $assignedAccountIds[0] ?? null;
            }
        }

        if ($user->isMarketing()) {
            $validated['user_id'] = $user->id;
        }

        // Auto-generate Customer Code (including soft deleted records)
        $lastCustomer = Customer::withTrashed()->orderBy('created_at', 'desc')->first();
        $sequence = $lastCustomer ? ((int) substr($lastCustomer->customer_code, -4)) + 1 : 1;
        $validated['customer_code'] = 'CUST-' . date('ym') . str_pad($sequence, 4, '0', STR_PAD_LEFT);
        $validated['api_sync_status'] = 'pending';
        $validated['currency'] = $validated['currency'] ?: 'IDR';

        $customer = Customer::create($validated);

        $this->notifyAssignedUser($customer);

        return redirect()->route('customers.index')->with('success', 'Customer Master Data created successfully.');
    }

    public function show(Customer $customer)
    {
        $this->authorizeAccess($customer);
        $customer->load(['account', 'user', 'orders.items.product']);
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $this->authorizeAccess($customer);
        $user = auth()->user();
        $accounts = $user->role === 'superadmin' ? Account::all() : $user->accounts;
        $products = Product::whereIn('account_id', $accounts->pluck('id'))->get()->groupBy('account_id');
        
        return view('customers.edit', compact('customer', 'accounts', 'products'));
    }

    public function update(Request $request, Customer $customer)
    {
        $this->authorizeAccess($customer);
        $user = auth()->user();
        $oldUserId = $customer->user_id;
        
        $validated = $request->validate([
            'account_id' => $user->role === 'superadmin' ? 'required|uuid|exists:accounts,id' : 'nullable',
            'user_id' => 'nullable|uuid|exists:users,id',
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'type' => 'required|in:individual,corporate',
            'status' => 'required|in:ACTIVE,INACTIVE,BLACKLIST,ARCHIVED',
            'whatsapp' => 'nullable|string|max:20',
            'alt_phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'location' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'source' => 'nullable|string|max:255',
            'source_reference' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high',
            'payment_term' => 'nullable|string|max:100',
            'currency' => 'nullable|string|max:10',
            'tax_type' => 'nullable|string|max:50',
            'npwp' => 'nullable|string|max:50',
            'follow_up_date' => 'nullable|date',
            'important_chat' => 'nullable|string',
        ]);

        $customer->update($validated);

        // Notify if ownership changed
        if ($customer->user_id && $customer->user_id !== $oldUserId) {
            $this->notifyAssignedUser($customer);
        }

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $this->authorizeAccess($customer);
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function export()
    {
        return Excel::download(new CustomersExport, 'customers_' . date('Y-m-d') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new CustomersImport, $request->file('file'));
        return redirect()->route('customers.index')->with('success', 'Customers imported successfully.');
    }

    public function downloadTemplate()
    {
        return Excel::download(new class implements \Maatwebsite\Excel\Concerns\WithHeadings {
            public function headings(): array {
                return [
                    'Nama Panggil',
                    'Nama Perusahaan',
                    'PIC Nama',
                    'PIC Jabatan',
                    'Tipe Entitas',
                    'Status',
                    'Email',
                    'Nomor WA',
                    'Telepon Alternatif',
                    'Alamat Lengkap',
                    'Provinsi',
                    'Negara',
                    'Kode Pos',
                    'Source Lead',
                    'Source Reference',
                    'Prioritas',
                    'Term of Payment',
                    'Mata Uang',
                    'Tipe Pajak',
                    'NPWP',
                    'Sales PIC Email',
                    'Perusahaan Unit',
                    'Catatan Internal',
                ];
            }
        }, 'template_customer_master.xlsx');
    }

    protected function notifyAssignedUser(Customer $customer)
    {
        if ($customer->user) {
            $customer->user->notify(new \App\Notifications\CustomerAssignedNotification($customer));
            
            if ($customer->user->manager) {
                $customer->user->manager->notify(new \App\Notifications\CustomerAssignedNotification($customer));
            }
        }
    }

    protected function authorizeAccess(Customer $customer)
    {
        $user = auth()->user();
        if ($user->role === 'superadmin') return;

        $assignedAccountIds = $user->accounts()->pluck('accounts.id')->toArray();
        if (!in_array($customer->account_id, $assignedAccountIds)) {
            abort(403, 'Unauthorized company access.');
        }
    }
}
