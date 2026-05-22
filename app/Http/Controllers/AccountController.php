<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::withCount(['users', 'contacts', 'orders'])->latest()->paginate(10);
        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:accounts,name',
            'industry' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'tax_id' => 'nullable|string|max:255',
        ]);

        Account::create($validated);

        return redirect()->route('accounts.index')->with('success', 'Company created successfully.');
    }

    public function edit(Account $account)
    {
        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:accounts,name,' . $account->id,
            'industry' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'tax_id' => 'nullable|string|max:255',
        ]);

        $account->update($validated);

        return redirect()->route('accounts.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(Account $account)
    {
        if ($account->contacts()->count() > 0) {
            return back()->with('error', 'Cannot delete company with active customers.');
        }

        $account->delete();
        return redirect()->route('accounts.index')->with('success', 'Company archived successfully.');
    }
}
