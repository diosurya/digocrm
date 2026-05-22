<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->role === 'superadmin') {
            $users = User::with(['manager', 'accounts'])->latest()->paginate(10);
        } elseif ($user->role === 'manager_marketing') {
            $users = User::where('parent_id', $user->id)
                ->orWhere('id', $user->id) // Managers can see themselves
                ->with(['manager', 'accounts'])
                ->latest()
                ->paginate(10);
        } else {
            abort(403, 'Unauthorized access to user list.');
        }

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $user = auth()->user();
        $accounts = $user->role === 'superadmin' ? Account::all() : $user->accounts;
        
        // Managers can only create 'marketing' role users
        $roles = \App\Models\Role::query();
        if ($user->role === 'manager_marketing') {
            $roles->where('slug', 'marketing');
        }
        $roles = $roles->get();

        $managers = User::whereIn('role', ['superadmin', 'manager_marketing'])->get();
        
        return view('users.create', compact('managers', 'accounts', 'roles'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'whatsapp' => 'nullable|string|max:20',
            'role' => 'required|in:superadmin,manager_marketing,marketing',
            'parent_id' => 'nullable|uuid|exists:users,id',
            'account_ids' => 'required|array',
            'account_ids.*' => 'exists:accounts,id',
        ]);

        // Authorization check for Managers
        if ($user->role === 'manager_marketing') {
            if ($validated['role'] !== 'marketing') abort(403, 'Managers can only create Marketing users.');
            $validated['parent_id'] = $user->id; // Forced assignment to this manager
        }

        $validated['password'] = Hash::make($validated['password']);
        
        $newUser = User::create($validated);
        $newUser->accounts()->sync($request->account_ids);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $this->authorizeAction($user);

        $currentUser = auth()->user();
        $managers = User::whereIn('role', ['superadmin', 'manager_marketing'])->where('id', '!=', $user->id)->get();
        $accounts = Account::all();
        
        $roles = \App\Models\Role::query();
        if ($currentUser->role === 'manager_marketing') {
            $roles->where('slug', 'marketing');
        }
        $roles = $roles->get();

        $userAccountIds = $user->accounts()->pluck('accounts.id')->toArray();
        return view('users.edit', compact('user', 'managers', 'accounts', 'userAccountIds', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeAction($user);
        $currentUser = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'whatsapp' => 'nullable|string|max:20',
            'role' => 'required|in:superadmin,manager_marketing,marketing',
            'parent_id' => 'nullable|uuid|exists:users,id',
            'account_ids' => 'required|array',
            'account_ids.*' => 'exists:accounts,id',
        ]);

        // Authorization check for Managers
        if ($currentUser->role === 'manager_marketing') {
            if ($validated['role'] !== 'marketing') abort(403, 'Managers can only update to Marketing role.');
            $validated['parent_id'] = $currentUser->id;
        }

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);
        $user->accounts()->sync($request->account_ids);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->authorizeAction($user);
        if ($user->id === auth()->id()) abort(403, 'You cannot delete yourself.');
        
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User removed successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\UsersImport, $request->file('file'));
        
        return redirect()->route('users.index')->with('success', 'Users imported successfully.');
    }

    public function downloadTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new class implements \Maatwebsite\Excel\Concerns\WithHeadings {
            public function headings(): array {
                return [
                    'Nama',
                    'Email',
                    'Password',
                    'WhatsApp',
                    'Role',
                    'Perusahaan', // Comma separated names
                ];
            }
        }, 'template_users.xlsx');
    }

    protected function authorizeAction(User $targetUser)
    {
        $currentUser = auth()->user();
        if ($currentUser->role === 'superadmin') return;

        // Manager can only edit their own subordinates or themselves
        if ($currentUser->role === 'manager_marketing') {
            if ($targetUser->parent_id === $currentUser->id || $targetUser->id === $currentUser->id) {
                return;
            }
        }

        abort(403, 'You do not have permission to manage this user.');
    }
}
