<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Laravel SB Admin 2">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AppHub Admin</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/b9aa2e9492.js" crossorigin="anonymous"></script>
    <!-- Favicon -->
    <link href="{{ asset('favicon.svg') }}" rel="icon" type="image/svg+xml">
</head>
<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center gap-2 px-3 bg-white" href="{{ url('/home') }}">
            <!-- Logo -->
            <div class="sidebar-brand-icon">
                <img src="{{ asset('img/apphub-mark.svg') }}" 
                    alt="AppHub" 
                    style="max-width: 40px; height: auto;">
            </div>

            <!-- Text -->
            <div class="sidebar-brand-text">
                <h2 class="text-lg lg:text-xl font-extrabold leading-tight mb-0">
                    <span class="text-custom-blue-100">App</span><span class="text-custom-red font-extrabold">Hub</span>
                </h2>
            </div>
        </a>


        <!-- Nav Item - Dashboard -->
        <li class="nav-item {{ Nav::isRoute('home') }}">
            <a class="nav-link" href="{{ route('home') }}">
                <i class="fas fa-fw fa-house"></i>
                <span>{{ __('Dashboard') }}</span></a>
        </li>

        <!-- Heading -->

        @hasanyrole('superadmin|admin')
        <li class="nav-item {{ Nav::isRoute('list') }}">
            <a class="nav-link" href="{{ route('list') }}">
                <i class="fas fa-fw fa-list"></i>
                <span>{{ __('Manajemen Konten') }}</span>
            </a>
        </li>

        <!-- <li class="nav-item {{ Nav::isRoute('website-type') }}">
            <a class="nav-link" href="{{ route('website-type') }}">
                <i class="fas fa-fw fa-globe"></i>
                <span>{{ __('Website Type') }}</span>
            </a>
        </li>


        <li class="nav-item {{ Nav::isRoute('business-categories') }}">
            <a class="nav-link" href="{{ route('business-categories') }}">
                <i class="fas fa-fw fa-briefcase"></i>
                <span>{{ __('Business Owner') }}</span>
            </a>
        </li> -->

        <!-- <li class="nav-item {{ Nav::isRoute('user') }}">
            <a class="nav-link" href="{{ route('user') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>{{ __('Users Management') }}</span>
            </a>
        </li>

        <li class="nav-item {{ Nav::isRoute('role') }}">
            <a class="nav-link" href="{{ route('role') }}">
                <i class="fas fa-fw fa-id-card-clip"></i>
                <span>{{ __('Role Management') }}</span>
            </a>
        </li> -->

        <li class="nav-item">
            <x-dropdown align="right" width="48" >
                <x-slot name="trigger">
                    <a href="#" class="nav-link dropdown-toggle flex items-left gap-1" @click.prevent>
                        <i class="fas fa-fw fa-solid fa-users-gear"></i>
                        <span>{{ __('Manajemen Pengguna') }}</span>
                    </a>
                </x-slot>

                <x-slot name="content">
                    <a class="block px-2 py-2 text-sm text-gray-700 hover:bg-gray-300"
                    href="{{ route('user') }}">
                    <i class="fas fa-fw fa-solid fa-users"></i>
                        Kelola Akun
                    </a>
                    <a class="block px-2 py-2 text-sm text-gray-700 hover:bg-gray-300"
                    href="{{ route('role') }}">
                    <i class="fas fa-fw fa-solid fa-users-rectangle"></i>
                        Kelola Akses
                    </a>
                </x-slot>
            </x-dropdown>
        </li>

        <li class="nav-item">
            <x-dropdown align="right" width="48" >
                <x-slot name="trigger">
                    <a href="#" class="nav-link dropdown-toggle flex items-left gap-1" @click.prevent>
                        <i class="fas fa-fw fa-solid fa-gear"></i>
                        <span>{{ __('Pengaturan') }}</span>
                    </a>
                </x-slot>

                <x-slot name="content">
                    <a class="block px-2 py-2 text-sm text-gray-700 hover:bg-gray-300"
                    href="{{ route('website-type') }}">
                    <i class="fas fa-fw fa-solid fa-table-list"></i>
                        Tipe Website
                    </a>
                    <a class="block px-2 py-2 text-sm text-gray-700 hover:bg-gray-300"
                    href="{{ route('business-categories') }}">
                    <i class="fas fa-fw fa-solid fa-network-wired"></i>
                        Pemilik Website
                    </a>
                </x-slot>
            </x-dropdown>
        </li>

        @endhasanyrole

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Search -->
                <!-- <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Cari..." aria-label="Cari" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form> -->

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                    <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                    <!-- <li class="nav-item dropdown no-arrow d-sm-none">
                        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-search fa-fw"></i>
                        </a> -->
                        <!-- Dropdown - Messages -->
                        <!-- <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                            <form class="form-inline mr-auto w-100 navbar-search">
                                <div class="input-group">
                                    <input type="text" class="form-control bg-light border-0 small" placeholder="Cari..." aria-label="Cari" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button">
                                            <i class="fas fa-search fa-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div> -->
                    <!-- </li> -->


                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                            <figure class="img-profile rounded-circle avatar font-weight-bold" data-initial="{{ optional(Auth::user())->name[0] ?? 'U' }}"></figure>
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                {{ __('Logout') }}
                            </a>
                        </div>
                    </li>

                </ul>

            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">
                @include('items.part.alert')
                @yield('main-content')

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Hak Cipta &copy;<a href="" target="_blank"></a>{{ now()->year }} - Seluruh hak dilindungi</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Ready to Leave?') }}</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih "Keluar" di bawah jika Anda ingin mengakhiri sesi saat ini.</div>
            <div class="modal-footer">
                <button class="btn btn-link" type="button" data-dismiss="modal">{{ __('Cancel') }}</button>
                <a class="btn btn-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
<script src="//unpkg.com/alpinejs" defer></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
