<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    // ✅ Menampilkan semua role
    public function index(Request $request)
    {
        $query = Role::query();

        // Fitur search (jika ada request search)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        $roles = $query->orderBy('created_at', 'desc')->paginate(10); // Pagination

        return view('items.role', compact('roles'));
    }

    // ✅ Menampilkan form tambah role (bisa pakai modal jika mau modular)
    public function create()
    {
        return view('role.create');
    }

    // ✅ Menyimpan role baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'guard_name' => 'required',
        ]);

        Role::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);

        return redirect()->route('role')->with('success', 'Role berhasil ditambahkan!');
    }

    // ✅ Menampilkan form edit role (bisa pakai modal juga)
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('role.edit', compact('role'));
    }

    // ✅ Update role
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'guard_name' => 'required',
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);

        return redirect()->route('role')->with('success', 'Role berhasil diperbarui!');
    }

    // ✅ Hapus role
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('role')->with('success', 'Role berhasil dihapus!');
    }

    // ✅ Tambahkan ini di RoleController
    public function show($id)
    {
        $role = Role::findOrFail($id);
        return view('role.show', compact('role')); // Jika tidak ada view, bisa return json atau redirect
    }

    // RoleController.php

    public function filter(Request $request)
    {
        $query = Role::query();

        // Jika ada filter nama role
        if ($request->has('name') && $request->name != '') {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $roles = $query->orderBy('created_at', 'desc')->paginate(10);

        // Pastikan return ke view index atau view yang kamu gunakan untuk list role
        return view('items.role', compact('roles'));
    }

    // RoleController.php

    public function search(Request $request)
    {
        $query = Role::query();

        // Jika ada keyword pencarian
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                ->orWhere('guard_name', 'like', '%' . $keyword . '%');
            });
        }

        $roles = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Pastikan arahkan ke view utama role
        return view('items.role', compact('roles'));
    }


}
