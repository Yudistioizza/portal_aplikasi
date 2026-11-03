@extends('layouts.admin')

@section('main-content')
<div class="container">
    <h2 class="fw-bold mb-4">Manajemen Pengguna</h2>

    @include('items.part.alert-success-user') <!-- Notifikasi sukses -->
    
    <form action="{{ route('user.search') }}" method="GET" class="row g-2 align-items-center mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Cari nama atau email" value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <select name="role" class="form-select">
                <option value="">-- Filter berdasarkan Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 d-flex">
            <button type="submit" class="btn btn-outline-primary me-2">Cari</button>
            <a href="{{ route('user') }}" class="btn btn-outline-secondary">Atur Ulang</a>
        </div>
    </form>


    <!-- <button onclick="window.location.href='{{ route('user.create') }}'" class="btn btn-primary mb-3">Add User</button> -->

    @include('items.part.table-list-user') <!-- Table list user -->

    <div class="mt-4">
        {{ $users->withQueryString()->links() }} <!-- Agar paginasi membawa query search & filter -->
    </div>
</div>

@include('items.part.delete-script-user') <!-- Script untuk delete -->
@endsection
