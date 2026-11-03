@extends('layouts.admin')

@section('main-content')
        <h2 class="fw-bold mb-4">Pemilik Bisnis</h2>

    <!-- Button Tambah -->
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addModal">
        Tambah Business Owner
    </button>

    <!-- Include table list -->
    @include('items.part.business-category-list')

    <!-- Include modal -->
    @include('items.part.business-category-modal')
@endsection