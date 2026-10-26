<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'KopiLink - Sistem Traceability Kopi Berbasis SDGs')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Traceability Kopi, KopiLink, Robusta Pasak Piang, Liberika Padi Jaya, SDGs Coffee" name="keywords">
    <meta content="KopiLink adalah platform keterlacakan rantai pasok kopi dari Kebun ke Cangkir (Crop to Cup) berbasis SDGs." name="description">

    <!-- Favicon -->
    <link href="{{ asset('img/favicon.ico') }}" rel="icon">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />

    <!-- Customized Koppee Bootstrap Stylesheet (Preserved & Kept) -->
    <link href="{{ asset('css/style.min.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body>
    <!-- Navbar Start -->
    <div class="container-fluid p-0 nav-bar">
        <nav class="navbar navbar-expand-lg bg-none navbar-dark py-3">
            <a href="{{ route('home') }}" class="navbar-brand px-lg-4 m-0 d-flex align-items-center">
                <i class="fa fa-coffee text-primary mr-2 fa-2x"></i>
                <div>
                    <h1 class="m-0 display-4 text-uppercase text-white" style="letter-spacing: 2px;">KOPPEE</h1>
                    <small class="text-primary font-weight-bold" style="font-size: 11px; letter-spacing: 3px; display: block; margin-top: -8px;">KOPILINK TRACEABILITY</small>
                </div>
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav ml-auto p-4">
                    <a href="{{ route('home') }}" class="nav-item nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('scan') }}" class="nav-item nav-link {{ request()->routeIs('scan') ? 'active' : '' }}">
                        <i class="fa fa-qrcode text-primary mr-1"></i> Scan QR Kopi
                    </a>
                    <a href="{{ route('about') }}" class="nav-item nav-link {{ request()->routeIs('about') ? 'active' : '' }}">Tentang</a>
                    <a href="{{ route('service') }}" class="nav-item nav-link {{ request()->routeIs('service') ? 'active' : '' }}">Layanan</a>
                    <a href="{{ route('donasi') }}" class="nav-item nav-link {{ request()->routeIs('donasi') ? 'active' : '' }}">Donasi</a>
                    <a href="{{ route('contact') }}" class="nav-item nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Kontak</a>
                    
                    @if(Auth::guard('petani')->check())
                        <a href="{{ route('petani.dashboard') }}" class="nav-item nav-link btn btn-primary text-secondary font-weight-bold ml-lg-3 px-3 py-2 mt-2 mt-lg-0" style="color: #33211D !important;">
                            <i class="fa fa-tachometer-alt mr-1"></i> Dashboard Petani
                        </a>
                    @elseif(Auth::guard('coffee_shop')->check())
                        <a href="{{ route('coffeeshop.dashboard') }}" class="nav-item nav-link btn btn-primary text-secondary font-weight-bold ml-lg-3 px-3 py-2 mt-2 mt-lg-0" style="color: #33211D !important;">
                            <i class="fa fa-store mr-1"></i> Dashboard Roastery
                        </a>
                    @elseif(Auth::guard('web')->check())
                        <a href="{{ route('admin.dashboard') }}" class="nav-item nav-link btn btn-warning text-dark font-weight-bold ml-lg-3 px-3 py-2 mt-2 mt-lg-0" style="color: #111 !important; background-color: #DA9F5B;">
                            <i class="fa fa-user-shield mr-1"></i> Panel Admin
                        </a>
                    @else
                        <div class="nav-item dropdown ml-lg-3 mt-2 mt-lg-0">
                            <a href="#" class="nav-link dropdown-toggle btn btn-outline-primary px-3 py-2" data-toggle="dropdown" style="border-radius: 4px;">
                                <i class="fa fa-user-circle mr-1"></i> Masuk / Daftar
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ route('login') }}" class="dropdown-item"><i class="fa fa-sign-in-alt text-primary mr-2"></i> Login Akun</a>
                                <a href="{{ route('register.petani') }}" class="dropdown-item"><i class="fa fa-user-plus text-primary mr-2"></i> Daftar Petani Baru</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Flash Message Container -->
    @if(session('status') && !request()->routeIs('contact'))
        <div class="container mt-4">
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="background-color: #DA9F5B; color: #111;">
                <i class="fa fa-check-circle mr-2"></i> {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="container mt-4">
            <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fa fa-exclamation-triangle mr-2"></i> {{ session('warning') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="container mt-4">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif

    @yield('content')

    <!-- Footer Start -->
    <div class="container-fluid footer text-white mt-5 pt-5 px-0 position-relative overlay-top">
        <div class="row mx-0 pt-5 px-sm-3 px-lg-5 mt-4">
            <div class="col-lg-4 col-md-6 mb-5">
                <h4 class="text-white text-uppercase mb-4" style="letter-spacing: 3px;">KOPILINK TRACEABILITY</h4>
                <p class="m-0">Platform transparansi rantai pasok kopi hulu ke hilir. Menghubungkan Kelompok Tani Pasak Piang (Robusta) dan Padi Jaya (Liberika) dengan *Coffee Shop* mitra demi perdagangan berkeadilan (*Fair Trade*) dan kelestarian hutan agroforestri.</p>
                <div class="d-flex justify-content-start mt-4">
                    <a class="btn btn-lg btn-outline-light btn-lg-square mr-2" href="#"><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-lg btn-outline-light btn-lg-square mr-2" href="#"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-lg btn-outline-light btn-lg-square mr-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-lg btn-outline-light btn-lg-square" href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-5">
                <h4 class="text-white text-uppercase mb-4" style="letter-spacing: 3px;">Pilar SDGs Terhubung</h4>
                <div class="d-flex flex-column justify-content-start">
                    <p class="text-white mb-2"><i class="fa fa-leaf text-primary mr-2"></i><strong>SDG 15 - Life on Land:</strong> Konservasi hutan & pemetaan koordinat lahan kebun.</p>
                    <p class="text-white mb-2"><i class="fa fa-chart-line text-primary mr-2"></i><strong>SDG 8 - Decent Work:</strong> Peningkatan kesejahteraan ekonomi petani mandiri.</p>
                    <p class="text-white mb-2"><i class="fa fa-shield-alt text-primary mr-2"></i><strong>SDG 12 - Responsible Consumption:</strong> Keaslian asal-usul & anti-greenwashing.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-5">
                <h4 class="text-white text-uppercase mb-4" style="letter-spacing: 3px;">Kontak & Alamat</h4>
                <p><i class="fa fa-map-marker-alt mr-2"></i>Sentra Kopi Agroforestri, Kalimantan Barat, Indonesia</p>
                <p><i class="fa fa-phone-alt mr-2"></i>+62 812-3456-7890</p>
                <p><i class="fa fa-envelope mr-2"></i>traceability@kopilink.id</p>
            </div>
        </div>
        <div class="container-fluid text-center text-white border-top mt-4 py-4 px-sm-3 px-md-5" style="border-color: rgba(256, 256, 256, .1) !important;">
            <p class="mb-2 text-white">Copyright &copy; {{ date('Y') }} <a class="font-weight-bold" href="#">KopiLink Traceability</a>. All Rights Reserved.</p>
            <p class="m-0 text-white">Preserved with Koppee Design by <a class="font-weight-bold" href="https://htmlcodex.com">HTML Codex</a></p>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa fa-angle-double-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('js/main.js') }}"></script>

    @stack('scripts')
</body>
</html>
