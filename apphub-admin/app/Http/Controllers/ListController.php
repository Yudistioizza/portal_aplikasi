<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MApps;
use App\Models\WebsiteType;
use App\Models\BusinessCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ListController extends Controller
{
    // Menampilkan daftar konten (index + search & filter)
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $websiteTypeId = $request->input('website_type_id');
        $businessCategoryId = $request->input('business_category_id');
        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'asc');

        $query = MApps::query();

        // Filter search (nama dan deskripsi)
        if ($search) {
            $searchTerm = strtolower($search);
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(app_name) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereRaw('LOWER(app_desc) LIKE ?', ["%{$searchTerm}%"]);
            });
        }

        // Filter status
        if ($status) {
            $query->where('status', $status);
        }

        // Filter website type
        if ($websiteTypeId) {
            $query->where('website_type_id', $websiteTypeId);
        }

        // Filter business category
        if ($businessCategoryId) {
            $query->where('business_category_id', $businessCategoryId);
        }

        // Sorting
        $allowedSorts = ['id', 'app_name', 'created_at', 'updated_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $order);
        } else {
            $query->orderBy('created_at', 'asc');
        }

        $konten = $query->paginate(10)->appends([
            'search' => $search,
            'status' => $status,
            'website_type_id' => $websiteTypeId,
            'business_category_id' => $businessCategoryId,
            'sort' => $sort,
            'order' => $order,
        ]);

        // Logos are stored as bytes; the view needs a data URI
        foreach ($konten as $item) {
            $item->app_logo = $this->logoDataUri($item->app_logo) ?? $this->defaultLogo();
        }

        // Ambil data WebsiteType dan BusinessCategory yang aktif
        $websiteTypes = WebsiteType::active()->get();
        $businessCategories = BusinessCategory::active()->get();

        return view('items.list', compact(
            'konten', 'search', 'status',
            'websiteTypeId', 'businessCategoryId',
            'sort', 'order',
            'websiteTypes', 'businessCategories'
        ));
    }


    // Menyimpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'nullable|string',
            'link_aplikasi' => 'nullable|url',
            'website_type_id' => 'required|exists:website_types,id',
            'business_category_id' => 'required|array|min:1',
            'business_category_id.*' => 'exists:business_categories,id',
        ]);

        // Handle logo
        $binaryData = ($request->hasFile('logo') && $request->file('logo')->isValid())
            ? file_get_contents($request->file('logo')->getRealPath())
            : $this->defaultLogoBytes();

        $logoBinary = $this->toDatabaseBinary($binaryData);

        $mApp = MApps::create([
            'app_name' => $request->nama,
            'app_desc' => $request->deskripsi,
            'app_logo' => $logoBinary,
            'app_link' => $request->link_aplikasi,
            'website_type_id' => $request->website_type_id,
            'app_order' => MApps::max('app_order') + 1,
            'created_by' => Auth::id() ?? 1,
        ]);

        // Sync business categories
        $mApp->businessCategories()->sync($request->business_category_id);

        return redirect()->route('list')->with('success', 'Konten berhasil ditambahkan!');
    }

    // Update konten
    public function update(Request $request, $id)
    {
        $konten = MApps::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'nullable|string',
            'link_aplikasi' => 'nullable|url',
            'website_type_id' => 'required|exists:website_types,id',
            'business_category_id' => 'required|array|min:1',
            'business_category_id.*' => 'exists:business_categories,id',
        ]);

        // Update data dasar
        $konten->app_name = $request->nama;
        $konten->app_desc = $request->deskripsi;
        $konten->app_link = $request->link_aplikasi;
        $konten->website_type_id = $request->website_type_id;
        $konten->modified_by = Auth::id();
        $konten->updated_at = now();

        // Update logo jika ada upload baru
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $binaryLogo = file_get_contents($request->file('logo')->getRealPath());
            $konten->app_logo = $this->toDatabaseBinary($binaryLogo);
        }

        $konten->save();

        // Sync business categories
        $konten->businessCategories()->sync($request->business_category_id);

        return redirect()->route('list')->with('success', 'Konten berhasil diperbarui!');
    }


    // Menghapus konten
    public function delete($id)
    {
        $konten = MApps::findOrFail($id);
        $konten->delete();

        return redirect()->route('list')->with('success', 'Konten berhasil dihapus!');
    }

    // ----- logo helpers -----

    private const DEFAULT_LOGO = 'img/default-app.svg';

    /** Bytes as stored (PostgreSQL returns a stream resource, other drivers a string) to a data URI. */
    private function logoDataUri($value): ?string
    {
        if (empty($value)) {
            return null;
        }
        $bytes = is_resource($value) ? stream_get_contents($value) : $value;
        if ($bytes === false || $bytes === '') {
            return null;
        }
        $mime = str_contains(substr($bytes, 0, 512), '<svg')
            ? 'image/svg+xml'
            : ((new \finfo(FILEINFO_MIME_TYPE))->buffer($bytes) ?: 'image/png');

        return 'data:' . $mime . ';base64,' . base64_encode($bytes);
    }

    private function defaultLogoBytes(): ?string
    {
        $path = public_path(self::DEFAULT_LOGO);

        return file_exists($path) ? file_get_contents($path) : null;
    }

    private function defaultLogo(): ?string
    {
        return $this->logoDataUri($this->defaultLogoBytes());
    }

    /** PostgreSQL needs decode(..., 'escape') for bytea; every other driver takes the raw bytes. */
    private function toDatabaseBinary(?string $bytes)
    {
        if ($bytes === null) {
            return null;
        }

        return \DB::connection()->getDriverName() === 'pgsql'
            ? \DB::raw("decode('" . pg_escape_bytea($bytes) . "', 'escape')")
            : $bytes;
    }

    // Toggle Status
    public function toggleStatus($id)
    {
        try {
            $konten = MApps::findOrFail($id);
            $oldStatus = $konten->status;
            $konten->status = ($konten->status === 'active') ? 'inactive' : 'active';
            $konten->save();

            $message = 'Status konten berhasil diubah dari ' . ucfirst($oldStatus) . ' menjadi ' . ucfirst($konten->status) . '.';
            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengubah status konten.');
        }
    }
}
