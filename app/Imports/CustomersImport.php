<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\Account;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomersImport implements ToModel, WithHeadingRow, WithValidation
{
    private $rowCount = 0;

    public function model(array $row)
    {
        $user = auth()->user();
        
        // 1. Determine Account (Company Unit)
        $account = null;
        if (!empty($row['perusahaan_unit'])) {
            $account = Account::where('name', 'like', '%' . trim($row['perusahaan_unit']) . '%')->first();
        }
        
        if (!$account) {
            $account = $user->accounts()->first();
        }

        if (!$account) return null;

        // 2. Determine Owner (Sales PIC)
        $owner = $user;
        if (!empty($row['sales_pic_email'])) {
            $foundOwner = User::where('email', trim($row['sales_pic_email']))->first();
            if ($foundOwner) $owner = $foundOwner;
        }

        // 3. Generate Customer Code (Incrementing for bulk)
        $this->rowCount++;
        $lastCustomer = Customer::orderBy('created_at', 'desc')->first();
        $sequence = $lastCustomer ? ((int) substr($lastCustomer->customer_code, -4)) + $this->rowCount : $this->rowCount;
        $customerCode = 'CUST-' . date('ym') . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        return new Customer([
            'account_id'     => $account->id,
            'user_id'        => $owner->id,
            'customer_code'  => $customerCode,
            'name'           => $row['nama_panggil'],
            'company_name'   => $row['nama_perusahaan'] ?? null,
            'contact_person' => $row['pic_nama'] ?? null,
            'job_title'      => $row['pic_jabatan'] ?? null,
            'type'           => (strtolower($row['tipe_entitas'] ?? '') == 'individual') ? 'individual' : 'corporate',
            'status'         => strtoupper($row['status'] ?? 'PROSPECT'),
            'whatsapp'       => $row['nomor_wa'] ?? null,
            'alt_phone'      => $row['telepon_alternatif'] ?? null,
            'email'          => $row['email'],
            'location'       => $row['alamat_lengkap'] ?? null,
            'province'       => $row['provinsi'] ?? null,
            'country'        => $row['negara'] ?? 'Indonesia',
            'postal_code'    => $row['kode_pos'] ?? null,
            'source'         => $row['source_lead'] ?? 'Manual Import',
            'source_reference' => $row['source_reference'] ?? null,
            'priority'       => strtoupper($row['prioritas'] ?? 'MEDIUM'),
            'payment_term'   => $row['term_of_payment'] ?? 'COD',
            'currency'       => $row['mata_uang'] ?? 'IDR',
            'tax_type'       => $row['tipe_pajak'] ?? 'NON-TAX',
            'npwp'           => $row['npwp'] ?? null,
            'important_chat' => $row['catatan_internal'] ?? null,
            'api_sync_status'=> 'PENDING',
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_panggil' => 'required|string',
            'email' => 'required|email',
        ];
    }
}
