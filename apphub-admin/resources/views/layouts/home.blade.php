@extends('layouts.admin')

@section('main-content')

    <!-- Page Heading -->
    <h1 class="h3 mb-4 fw-bold">{{ __('Dashboard') }}</h1>
    <h5 class="text-muted mb-4">Selamat datang, <span class="fw-semibold">{{ Auth::user()->name }}</span></h5>


    @if (session('success'))
    <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if (session('status'))
        <div class="alert alert-success border-left-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="row">
        
    </div>

    <div class="row">

        <!-- Content Column -->
        <div class="col-lg-6 mb-4">

        </div>

        <div class="col-lg-6 mb-4">

        </div>
    </div>
@endsection
