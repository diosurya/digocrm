<?php

namespace App\Exports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LeadsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Lead::with('account')->get();
    }

    public function headings(): array
    {
        return [
            'Kode Lead',
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

    public function map($lead): array
    {
        return [
            $lead->lead_code,
            $lead->account?->name,
            $lead->name,
            $lead->company_name,
            $lead->job_title,
            $lead->industry,
            $lead->city,
            $lead->email,
            $lead->phone,
            $lead->status,
            $lead->source,
            $lead->product,
            $lead->qualification,
            $lead->estimated_budget,
            $lead->estimated_deal_value,
            $lead->customer_needs,
            $lead->notes,
        ];
    }
}
