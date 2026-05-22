<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Simple import logic
        $user = User::create([
            'name'     => $row['nama'],
            'email'    => $row['email'],
            'password' => Hash::make($row['password'] ?? 'password123'),
            'role'     => $row['role'] ?? 'marketing',
            'whatsapp' => $row['whatsapp'] ?? null,
        ]);

        // Link to companies if provided (comma separated names or IDs)
        if (!empty($row['perusahaan'])) {
            $companyNames = explode(',', $row['perusahaan']);
            foreach ($companyNames as $name) {
                $account = Account::where('name', 'like', '%' . trim($name) . '%')->first();
                if ($account) {
                    $user->accounts()->attach($account->id);
                }
            }
        }

        return $user;
    }
}
