@extends('layouts.admin')

@section('main-content')
<div class="container">
    <h2 class="fw-bold mb-4">Manajemen Role</h2>

    @include('items.part.alert-success-role')

    <!-- <form method="GET" action="{{ route('role') }}" class="d-flex mb-3">
        <input type="text" name="search" placeholder="Cari role..." value="{{ request('search') }}" class="form-control me-2">
        <button type="submit" class="btn btn-primary">Cari</button>
    </form> -->

    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modal-create-role">
        Tambah Role
    </button>

    @include('items.part.modal-create-role') <!-- Modal Create Role -->

    <!-- Searchbar + Filter Role -->
    <form action="{{ route('role.search') }}" method="GET" class="row g-2 align-items-center mb-3">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Cari role" value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="role" class="form-select">
                <option value="">-- Filter berdasarkan Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 d-flex">
            <button type="submit" class="btn btn-outline-primary me-2">Cari</button>
            <a href="{{ route('role.filter') }}" class="btn btn-outline-secondary">Atur Ulang</a>
        </div>
    </form>

    @include('items.part.table-list-role')

    {{ $roles->links() }}
</div>
@endsection
