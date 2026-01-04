<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\PersonalTrainer;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('branch');
        
        if ($request->has('branch_id') && $request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }
        
        $users = $query->latest()->paginate(10);
        $branches = Branch::active()->get();
        
        return view('users.index', compact('users', 'branches'));
    }

    public function create()
    {
        $branches = Branch::active()->get();
        $personalTrainers = PersonalTrainer::where('is_active', true)
            ->whereDoesntHave('user')
            ->get();
        return view('users.create', compact('branches', 'personalTrainers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,staff,owner,pt',
            'branch_id' => 'nullable|exists:branches,id',
            'personal_trainer_id' => 'nullable|exists:personal_trainers,id|required_if:role,pt',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'branch_id' => $request->branch_id,
            'personal_trainer_id' => $request->personal_trainer_id,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $user->load(['branch', 'permissions']);
        $availablePermissions = UserPermission::getAvailablePermissions();
        return view('users.show', compact('user', 'availablePermissions'));
    }

    public function edit(User $user)
    {
        $branches = Branch::active()->get();
        $personalTrainers = PersonalTrainer::where('is_active', true)
            ->where(function($query) use ($user) {
                $query->whereDoesntHave('user')
                      ->orWhere('id', $user->personal_trainer_id);
            })
            ->get();
        $availablePermissions = UserPermission::getAvailablePermissions();
        $user->load('permissions');
        return view('users.edit', compact('user', 'branches', 'personalTrainers', 'availablePermissions'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,staff,owner,pt',
            'branch_id' => 'nullable|exists:branches,id',
            'personal_trainer_id' => 'nullable|exists:personal_trainers,id|required_if:role,pt',
            'permissions' => 'nullable|array',
            'permissions.*' => 'boolean'
        ]);

        $data = $request->except(['password', 'password_confirmation', 'permissions']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Update permissions
        if ($request->has('permissions')) {
            $user->permissions()->delete();
            
            foreach ($request->permissions as $permission => $granted) {
                if ($granted) {
                    $user->permissions()->create([
                        'permission' => $permission,
                        'granted' => true
                    ]);
                }
            }
        }

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}