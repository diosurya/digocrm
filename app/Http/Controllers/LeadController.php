<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Account;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Exports\LeadsExport;
use App\Imports\LeadsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Lead::query()->with('user');

        // RBAC & Account Scoping
        if ($user->role === 'superadmin') {
            if ($request->filled('account_id')) {
                $query->where('account_id', $request->account_id);
            }
        } elseif ($user->role === 'manager_marketing') {
            $assignedAccountIds = $user->accounts()->pluck('accounts.id')->toArray();
            $query->whereIn('account_id', $assignedAccountIds);
            
            $subordinateIds = $user->subordinates()->pluck('id')->toArray();
            $query->where(function($q) use ($user, $subordinateIds) {
                $q->whereNull('user_id')
                  ->orWhere('user_id', $user->id)
                  ->orWhereIn('user_id', $subordinateIds);
            });
        } else {
            $assignedAccountIds = $user->accounts()->pluck('accounts.id')->toArray();
            $query->whereIn('account_id', $assignedAccountIds)
                  ->where('user_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('lead_code', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $leads = $query->latest()->paginate(15)->withQueryString();
        return view('leads.index', compact('leads'));
    }

    public function create()
    {
        $user = auth()->user();
        $accounts = $user->role === 'superadmin' ? Account::all() : $user->accounts;
        return view('leads.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'account_id' => 'required|uuid|exists:accounts,id',
            'user_id' => 'nullable|uuid|exists:users,id',
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:NEW,CONTACTED,FOLLOW_UP,QUALIFIED,PROPOSAL,NEGOTIATION,WON,LOST',
            'source' => 'required|string|max:255',
            'source_reference' => 'nullable|string|max:255',
            'product' => 'nullable|string|max:255',
            'qualification' => 'nullable|string',
            'estimated_budget' => 'nullable|numeric|min:0',
            'estimated_deal_value' => 'nullable|numeric|min:0',
            'customer_needs' => 'nullable|string',
            'next_followup_at' => 'nullable|date',
        ]);

        // Auto-generate Lead Code (including trashed)
        $lastLead = Lead::withTrashed()->orderBy('created_at', 'desc')->first();
        $sequence = $lastLead ? ((int) substr($lastLead->lead_code, -4)) + 1 : 1;
        $validated['lead_code'] = 'LEAD-' . date('ym') . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        // Odoo Style: Initial tracking
        $validated['last_activity_at'] = now();
        $validated['status_updated_at'] = now();

        // Mandatory Next Activity Logic: Default to +2 days if not set
        if (empty($validated['next_followup_at'])) {
            $validated['next_followup_at'] = now()->addDays(2);
        }

        if ($user->isMarketing() && empty($validated['user_id'])) {
            $validated['user_id'] = $user->id;
        }

        $lead = Lead::create($validated);

        if ($lead->user_id) {
            $lead->user->notify(new \App\Notifications\CustomerAssignedNotification($lead));
        }

        return redirect()->route('leads.index')->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {
        $this->authorizeAccess($lead);
        $lead->load(['user', 'account', 'activities.user']);
        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $this->authorizeAccess($lead);
        $user = auth()->user();
        $accounts = $user->role === 'superadmin' ? Account::all() : $user->accounts;
        return view('leads.edit', compact('lead', 'accounts'));
    }

    public function update(Request $request, Lead $lead)
    {
        $this->authorizeAccess($lead);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:NEW,CONTACTED,FOLLOW_UP,QUALIFIED,PROPOSAL,NEGOTIATION,WON,LOST',
            'source' => 'required|string|max:255',
            'source_reference' => 'nullable|string|max:255',
            'qualification' => 'nullable|string',
            'estimated_budget' => 'nullable|numeric|min:0',
            'estimated_deal_value' => 'nullable|numeric|min:0',
            'customer_needs' => 'nullable|string',
            'next_followup_at' => 'nullable|date',
        ]);

        if ($lead->status !== $validated['status']) {
            $validated['status_updated_at'] = now();
        }

        $lead->update($validated);

        return redirect()->route('leads.index')->with('success', 'Lead updated successfully.');
    }

    public function export()
    {
        return Excel::download(new LeadsExport, 'leads_' . date('Y-m-d') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new LeadsImport, $request->file('file'));
        return redirect()->route('leads.index')->with('success', 'Leads imported successfully.');
    }

    public function downloadTemplate()
    {
        return Excel::download(new class implements \Maatwebsite\Excel\Concerns\WithHeadings {
            public function headings(): array {
                return [
                    'Account / Perusahaan',
                    'Nama Lengkap',
                    'Nama Perusahaan (Lead)',
                    'Jabatan',
                    'Industri',
                    'Kota',
                    'Email',
                    'Nomor Telepon',
                    'Status',
                    'Sumber',
                    'Produk',
                    'Kualifikasi',
                    'Estimasi Budget',
                    'Estimasi Deal',
                    'Kebutuhan Customer',
                    'Catatan',
                ];
            }
        }, 'template_import_leads.xlsx');
    }

    public function convertToCustomer(Lead $lead)
    {
        $this->authorizeAccess($lead);

        return DB::transaction(function () use ($lead) {
            // Generate Customer Code (including trashed)
            $lastCustomer = \App\Models\Customer::withTrashed()->orderBy('created_at', 'desc')->first();
            $sequence = $lastCustomer ? ((int) substr($lastCustomer->customer_code, -4)) + 1 : 1;
            $customerCode = 'CUST-' . date('ym') . str_pad($sequence, 4, '0', STR_PAD_LEFT);

            $customer = Customer::create([
                'account_id' => $lead->account_id,
                'user_id' => $lead->user_id,
                'customer_code' => $customerCode,
                'name' => $lead->name,
                'company_name' => $lead->company_name,
                'contact_person' => $lead->name,
                'job_title' => $lead->job_title,
                'email' => $lead->email,
                'whatsapp' => $lead->phone,
                'status' => 'ACTIVE',
                'source' => $lead->source,
                'source_reference' => $lead->source_reference,
                'location' => $lead->city,
                'type' => 'corporate',
                'api_sync_status' => 'pending',
                'currency' => 'IDR',
                'priority' => $lead->qualification == 'Hot' ? 'high' : 'medium',
            ]);

            $lead->update(['status' => 'WON', 'status_updated_at' => now()]);

            $lead->activities()->create([
                'user_id' => auth()->id(),
                'activity_type' => 'status_update',
                'result' => 'Lead converted to Customer Master: ' . $customerCode,
                'previous_status' => $lead->status,
                'new_status' => 'WON',
                'followup_date' => now(),
            ]);

            return redirect()->route('customers.show', $customer->id)->with('success', 'Lead successfully converted to Customer Master.');
        });
    }

    public function logActivity(Request $request, Lead $lead)
    {
        $this->authorizeAccess($lead);
        
        $validated = $request->validate([
            'activity_type' => 'required|in:CALL,WHATSAPP,EMAIL,VISIT,DEMO,PROPOSAL,MEETING,NOTE',
            'result' => 'required|string',
            'outcome' => 'nullable|string',
            'new_status' => 'required|in:NEW,CONTACTED,FOLLOW_UP,QUALIFIED,PROPOSAL,NEGOTIATION,WON,LOST',
            'next_followup_at' => 'nullable|date',
        ]);

        // Odoo Style: Mandatory Next Activity (Auto-set H+2 if empty)
        $nextFollowUp = $request->next_followup_at ?: now()->addDays(2);

        $previousStatus = $lead->status;

        // Create Activity Log
        $lead->activities()->create([
            'user_id' => auth()->id(),
            'activity_type' => $validated['activity_type'],
            'result' => $validated['result'],
            'outcome' => $request->outcome,
            'previous_status' => $previousStatus,
            'new_status' => $validated['new_status'],
            'followup_date' => now(),
        ]);

        // Update Lead Tracking (Pulse)
        $lead->update([
            'status' => $validated['new_status'],
            'status_updated_at' => ($previousStatus !== $validated['new_status']) ? now() : $lead->status_updated_at,
            'next_followup_at' => $nextFollowUp,
            'last_activity_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Activity logged. Next follow-up scheduled for ' . \Carbon\Carbon::parse($nextFollowUp)->format('d M Y'));
    }

    protected function authorizeAccess(Lead $lead)
    {
        $user = auth()->user();
        if ($user->role === 'superadmin') return;
        
        $assignedAccountIds = $user->accounts()->pluck('accounts.id')->toArray();
        if (!in_array($lead->account_id, $assignedAccountIds)) abort(403);

        if ($user->role === 'manager_marketing') {
            $subordinateIds = $user->subordinates()->pluck('id')->toArray();
            if ($lead->user_id === $user->id || in_array($lead->user_id, $subordinateIds)) return;
        }

        if ($user->isMarketing() && $lead->user_id === $user->id) return;

        abort(403);
    }
}
