@extends('layouts.admin')

@section('main-content')
    <h2 class="fw-bold mb-4">Tipe Website</h2>

    <!-- Button Tambah -->
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addModal">
        Tambah Website Type
    </button>

    <!-- Include list table -->
    @include('items.part.website-type-list')

    <!-- Include modal -->
    @include('items.part.website-type-modal')
@endsection
