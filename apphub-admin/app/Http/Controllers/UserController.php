<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the users with optional filtering.
     */
    public function index(Request $request)
    {
        $query = User::query()->with('roles'); // Include roles for relationship

        // Search berdasarkan nama & email (case-insensitive)
        if ($request->filled('search')) {
            $searchTerm = strtolower($request->input('search'));

            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(email) LIKE ?', ["%{$searchTerm}%"]);
            });
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->input('role'));
            });
        }

        // Filter berdasarkan status (active/inactive)
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Sorting dan pagination
        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Ambil semua roles untuk filter dropdown
        $roles = Role::all();

        return view('items.user', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all(); // Ambil role untuk assign ke user
        return view('user.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'status' => ['required', 'in:active,inactive'], // Validasi status
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => $request->status, // Set status aktif/non-aktif
        ]);

        // Assign role ke user (jika menggunakan pivot table atau lainnya)
        if ($request->filled('role_id')) {
            $user->roles()->attach($request->role_id);
        }

        return redirect()->route('user')->with('success', 'Pengguna berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::all(); // Tampilkan role untuk edit
        return view('user-edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = $request->status;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Update role (detach & attach untuk update many-to-many)
        if ($request->filled('role_id')) {
            $user->roles()->sync($request->role_id);
        }

        return redirect()->route('user')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user')->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Search & filter function moved to index for better integration.
     */
}