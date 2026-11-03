<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BusinessCategory;


class BusinessCategoryController extends Controller
{
    // Tampilkan semua business categories yang aktif
    public function index()
    {
        $categories = BusinessCategory::orderBy('id', 'asc')->get();
        return view('items.business-categories', compact('categories'));
    }

    // Simpan business category baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        BusinessCategory::create([
            'name' => $request->name,
            'status' => true,
            'created_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Kategori bisnis berhasil ditambahkan.');
    }

    // Update business category
    public function update(Request $request, BusinessCategory $businessCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $businessCategory->update([
            'name' => $request->name,
            'modified_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Kategori bisnis berhasil diperbarui.');
    }

    // Soft delete business category (set status = false)
    public function destroy(BusinessCategory $businessCategory)
    {
        $businessCategory->update([
            'status' => false,
            'modified_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Kategori bisnis berhasil dinonaktifkan.');
    }
}
