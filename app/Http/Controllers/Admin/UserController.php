<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'supplier' => User::where('role', 'supplier')->count(),
            'supervisor' => User::where('role', 'supervisor')->count(),
            'client' => User::where('role', 'client')->count(),
            'active_today' => User::whereDate('last_login_at', today())->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = ['admin', 'supplier', 'supervisor', 'client'];
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,supplier,supervisor,client',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load('orders');
        $orderStats = [
            'total' => $user->orders->count(),
            'total_spent' => $user->orders->sum('total_amount'),
            'completed' => $user->orders->where('status', 'delivered')->count(),
            'pending' => $user->orders->where('status', 'pending')->count(),
        ];

        return view('admin.users.show', compact('user', 'orderStats'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = ['admin', 'supplier', 'supervisor', 'client'];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,supplier,supervisor,client',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Update user status (active/inactive).
     */
    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$status} successfully!");
    }

    /**
     * Impersonate a user (login as user).
     */
    public function impersonate(User $user)
    {
        session(['impersonate' => auth()->id()]);
        auth()->login($user);

        return redirect()->route('dashboard')
            ->with('success', 'You are now impersonating ' . $user->name);
    }

    /**
     * Stop impersonating.
     */
    public function stopImpersonate()
    {
        $originalUserId = session('impersonate');
        if ($originalUserId) {
            session()->forget('impersonate');
            auth()->loginUsingId($originalUserId);

            return redirect()->route('admin.users.index')
                ->with('success', 'You are back to your admin account.');
        }

        return redirect()->route('admin.users.index');
    }
}
