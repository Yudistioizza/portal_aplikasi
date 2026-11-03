@extends('layouts.admin')

@section('main-content')
    <h2 class="fw-bold mb-4">Manajemen Konten</h2>

    <!-- @if(session('success'))
    <div id="success-alert" class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert" style="padding: 15px; border-radius: 8px;">
        <i class="bi bi-check-circle-fill me-2" style="font-size: 1.5rem;"></i>
        <div>
            {{ session('success') }}
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>

    <script>
        // Menghilangkan notifikasi otomatis setelah 3 detik
        setTimeout(() => {
            let alert = document.getElementById("success-alert");
            if (alert) {
                alert.style.transition = "opacity 0.5s ease-out";
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    </script>
    @endif -->

    <!-- Tombol Tambah Konten (Memunculkan Modal) -->
    <div class="d-flex align-items-center mb-3" style="gap: 10px;">
        <!-- Button -->
        <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">Tambahkan Konten</button>
    </div>

    <form action="{{ route('list') }}" method="GET" class="row g-2 align-items-center mb-4">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Cari Nama Aplikasi" value="{{ request('search') }}">
        </div>

        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <div class="col-md-3">
            <select name="website_type_id" class="form-select">
                <option value="">Semua Tipe Website</option>
                @foreach($websiteTypes as $type)
                    <option value="{{ $type->id }}" {{ request('website_type_id') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <select name="business_category_id" class="form-select">
                <option value="">Semua Pemilik Bisnis</option>
                @foreach($businessCategories as $category)
                    <option value="{{ $category->id }}" {{ request('business_category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 d-flex">
            <button type="submit" class="btn btn-outline-primary me-2">Cari</button>
            <a href="{{ route('list') }}" class="btn btn-outline-secondary">Atur Ulang</a>
        </div>
    </form>




    @include('items.part.table-list-content')

@include('items.part.modal-add-content')
@include('items.part.modal-edit-content')
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: "Yakin ingin menghapus?",
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, Hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("delete-form-" + id).submit();
            }
        });
    }
</script>
@endsection