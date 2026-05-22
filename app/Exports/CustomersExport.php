<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $user = auth()->user();
        $query = Customer::with(['account', 'user']);

        if ($user->role === 'superadmin') {
            // All data
        } elseif ($user->role === 'manager_marketing') {
            $assignedAccountIds = $user->accounts()->pluck('accounts.id')->toArray();
            $query->whereIn('account_id', $assignedAccountIds);
        } else {
            $query->where('user_id', $user->id);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Customer Code',
            'Nama Panggil',
            'Company Name',
            'Contact Person',
            'Job Title',
            'Status',
            'WhatsApp',
            'Alt Phone',
            'Email',
            'Address',
            'Province',
            'Country',
            'Postal Code',
            'Source',
            'Source Reference',
            'Priority',
            'Payment Term',
            'Currency',
            'Tax Type',
            'NPWP',
            'Account Owner (Sales)',
            'Company (Unit)',
            'ERP ID',
            'Sync Status',
            'Follow Up Date',
            'Last Contact',
            'Next Action',
            'Internal Notes'
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->customer_code,
            $customer->name,
            $customer->company_name,
            $customer->contact_person,
            $customer->job_title,
            strtoupper($customer->status),
            $customer->whatsapp,
            $customer->alt_phone,
            $customer->email,
            $customer->location,
            $customer->province,
            $customer->country,
            $customer->postal_code,
            $customer->source,
            $customer->source_reference,
            strtoupper($customer->priority),
            $customer->payment_term,
            $customer->currency,
            $customer->tax_type,
            $customer->npwp,
            $customer->user->name ?? 'Unassigned',
            $customer->account->name ?? '-',
            $customer->erp_customer_id,
            strtoupper($customer->api_sync_status),
            $customer->follow_up_date?->format('Y-m-d H:i'),
            $customer->last_contact_date?->format('Y-m-d H:i'),
            $customer->next_action,
            $customer->important_chat,
        ];
    }
}
