<?php

namespace App\Imports;

use App\Models\Lead;
use App\Models\Account;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class LeadsImport implements ToModel, WithHeadingRow, WithValidation
{
    private $rowCount = 0;

    public function model(array $row)
    {
        $user = auth()->user();
        $this->rowCount++;

        // 1. Determine Account (Company Unit)
        $accountName = $row['account_perusahaan'] ?? $row['account'] ?? null;
        $accountId = null;

        if ($accountName) {
            // Flexible search by name
            $account = Account::where('name', 'like', '%' . trim($accountName) . '%')->first();
            if ($account) {
                $accountId = $account->id;
            }
        }

        // If no account found, default to first assigned account for the user
        if (!$accountId) {
            $account = $user->accounts()->first();
            if ($account) {
                $accountId = $account->id;
            }
        }

        // 2. Generate Lead Code (Incrementing for bulk)
        $lastLead = Lead::withTrashed()->orderBy('created_at', 'desc')->first();
        $sequence = $lastLead ? ((int) substr($lastLead->lead_code, -4)) + $this->rowCount : $this->rowCount;
        $leadCode = 'LEAD-' . date('ym') . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        return new Lead([
            'lead_code'            => $row['kode_lead'] ?? $leadCode,
            'account_id'           => $accountId,
            'user_id'              => $user->id,
            'name'                 => $row['nama_lengkap'] ?? $row['nama'],
            'company_name'         => $row['nama_perusahaan'] ?? $accountName,
            'job_title'            => $row['jabatan'] ?? null,
            'industry'             => $row['industri'] ?? null,
            'city'                 => $row['kota'] ?? null,
            'email'                => $row['email'],
            'phone'                => $row['nomor_telepon'] ?? $row['phone'] ?? null,
            'status'               => strtoupper($row['status'] ?? 'NEW'),
            'source'               => $row['sumber'] ?? $row['source'] ?? 'Excel Import',
            'product'              => $row['produk'] ?? null,
            'qualification'        => $row['kualifikasi'] ?? 'Cold',
            'estimated_budget'     => $row['estimasi_budget'] ?? 0,
            'estimated_deal_value' => $row['estimasi_deal'] ?? 0,
            'customer_needs'       => $row['kebutuhan_customer'] ?? null,
            'notes'                => $row['catatan'] ?? null,
            'next_followup_at'     => now()->addDays(2),
            'status_updated_at'    => now(),
            'last_activity_at'     => now(),
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => 'required_without:nama',
            'email' => 'required|email',
        ];
    }
}
