<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WebsiteType;

class WebsiteTypeController extends Controller
{
    // Menampilkan data WebsiteType
    public function index()
    {
        $websiteTypes = WebsiteType::active()->get();
        return view('items.website-type', compact('websiteTypes'));
    }

    // Menambahkan WebsiteType baru
    public function store(Request $request)
    {
        // Validasi input nama
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Menyimpan data WebsiteType dengan status default 'aktif' (true)
        WebsiteType::create([
            'name' => $validated['name'],
            'status' => true, // Status otomatis aktif
            'created_by' => auth()->id(), // ID pengguna yang membuat
        ]);

        // Mengarahkan kembali ke halaman daftar WebsiteType dengan pesan sukses
        return redirect()->route('website-type')->with('success', 'Data berhasil disimpan!');
    }


    // Mengedit WebsiteType
    public function update(Request $request, WebsiteType $websiteType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $websiteType->update([
            'name' => $validated['name'],
            'status' => $validated['status'],
            'modified_by' => auth()->id(),
        ]);

        return redirect()->route('website-type');
    }

    public function destroy($id)
    {
        // Cari WebsiteType berdasarkan id
        $websiteType = WebsiteType::find($id);

        if ($websiteType) {
            // Anda dapat menghapus atau menonaktifkan data (soft delete jika Anda ingin menonaktifkan)
            $websiteType->delete();  // Atau jika menggunakan SoftDeletes: $websiteType->delete();

            return redirect()->route('website-type')->with('success', 'Tipe website berhasil dinonaktifkan');
        }

        return redirect()->route('website-type')->with('error', 'Tipe website tidak ditemukan');
    }
}
