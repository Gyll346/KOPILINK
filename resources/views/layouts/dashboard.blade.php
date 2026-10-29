<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Dashboard - KopiLink')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Favicon -->
    <link href="{{ asset('img/favicon.ico') }}" rel="icon">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Koppee Template CSS -->
    <link href="{{ asset('css/style.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --koppee-primary: #DA9F5B;
            --koppee-secondary: #33211D;
            --koppee-dark: #111111;
            --koppee-light: #FFFBF2;
        }

        body {
            background-color: #f7f3ed;
            font-family: "Montserrat", sans-serif;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        #dashboard-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        #sidebar {
            width: 270px;
            background: #251714;
            color: #fff;
            transition: all 0.3s;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1040;
            overflow-y: auto;
            border-right: 2px solid rgba(218, 159, 91, 0.2);
        }

        #sidebar.collapsed {
            margin-left: -270px;
        }

        #sidebar .sidebar-brand {
            padding: 24px 20px;
            background: #1c110f;
            border-bottom: 1px solid rgba(218, 159, 91, 0.2);
        }

        #sidebar .sidebar-brand h3 {
            color: #fff;
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        #sidebar .sidebar-heading {
            padding: 16px 20px 8px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #DA9F5B;
            font-weight: 700;
        }

        #sidebar .nav-link {
            padding: 12px 20px;
            color: #ddd;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            border-left: 4px solid transparent;
            transition: all 0.2s;
            text-decoration: none;
        }

        #sidebar .nav-link i {
            width: 26px;
            font-size: 16px;
            color: #DA9F5B;
        }

        #sidebar .nav-link:hover {
            color: #fff;
            background: rgba(218, 159, 91, 0.15);
            border-left-color: #DA9F5B;
        }

        #sidebar .nav-link.active {
            color: #111;
            background: #DA9F5B;
            border-left-color: #fff;
            font-weight: 600;
        }

        #sidebar .nav-link.active i {
            color: #111;
        }

        /* Content Area */
        #content-wrapper {
            flex: 1;
            margin-left: 270px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: all 0.3s;
        }

        #content-wrapper.expanded {
            margin-left: 0;
        }

        /* Topbar */
        .dashboard-topbar {
            height: 70px;
            background: #fff;
            border-bottom: 1px solid #e5dec9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .btn-toggle-sidebar {
            background: transparent;
            border: none;
            color: #33211D;
            font-size: 20px;
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            #sidebar {
                margin-left: -270px;
            }
            #sidebar.show {
                margin-left: 0;
            }
            #content-wrapper {
                margin-left: 0;
            }
        }

        .card-stat {
            border: none;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 2px 10px rgba(51, 33, 29, 0.08);
            transition: transform 0.2s;
        }

        .card-stat:hover {
            transform: translateY(-2px);
        }

        .btn-koppee {
            background-color: #DA9F5B;
            color: #111;
            font-weight: 600;
            border: none;
        }
        .btn-koppee:hover {
            background-color: #c98b45;
            color: #fff;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div id="dashboard-wrapper">
        <!-- Sidebar Navigation Start -->
        <nav id="sidebar">
            <div class="sidebar-brand">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    <h3 class="text-uppercase"><i class="fa fa-coffee text-primary mr-2"></i>KOPPEE</h3>
                    <div style="font-size: 11px; color: #DA9F5B; letter-spacing: 1px; font-weight: 600;">PORTAL TRACEABILITY</div>
                </a>
            </div>

            @if(Auth::guard('petani')->check())
                @php $petani = Auth::guard('petani')->user(); @endphp
                <div class="px-3 py-3 border-bottom border-dark d-flex align-items-center">
                    @if($petani->foto_profil)
                        <img src="{{ asset('storage/' . $petani->foto_profil) }}" alt="{{ $petani->nama }}" class="rounded-circle mr-3 shadow-sm border border-warning" style="width: 42px; height: 42px; object-fit: cover; flex-shrink: 0;">
                    @else
                        <div class="bg-primary text-secondary rounded-circle d-flex align-items-center justify-content-center mr-3 font-weight-bold" style="width: 42px; height: 42px; font-size: 18px; color: #33211D !important; flex-shrink: 0;">
                            <i class="fa fa-user"></i>
                        </div>
                    @endif
                    <div style="overflow: hidden;">
                        <div class="text-white font-weight-bold text-truncate" style="font-size: 14px;">{{ $petani->nama }}</div>
                        <span class="badge badge-warning" style="font-size: 10px; background-color: #DA9F5B; color: #111;">
                            Kelompok: {{ $petani->kelompok_tani }}
                        </span>
                    </div>
                </div>

                <div class="sidebar-heading">Menu Petani</div>
                <ul class="nav flex-column mb-4">
                    <li class="nav-item">
                        <a href="{{ route('petani.dashboard') }}" class="nav-link {{ request()->routeIs('petani.dashboard') ? 'active' : '' }}">
                            <i class="fa fa-tachometer-alt"></i> Ringkasan Utama
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('petani.profile') }}" class="nav-link {{ request()->routeIs('petani.profile') ? 'active' : '' }}">
                            <i class="fa fa-user-circle"></i> Profil Petani
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('petani.lahan.index') }}" class="nav-link {{ request()->routeIs('petani.lahan.*') ? 'active' : '' }}">
                            <i class="fa fa-map-marked-alt"></i> Lahan Kebun Saya
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('petani.ceri.index') }}" class="nav-link {{ request()->routeIs('petani.ceri.*') ? 'active' : '' }}">
                            <i class="fa fa-apple-alt"></i> Panen Ceri Kopi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('petani.produksi.index') }}" class="nav-link {{ request()->routeIs('petani.produksi.*') ? 'active' : '' }}">
                            <i class="fa fa-seedling"></i> Produksi Green Bean
                        </a>
                    </li>
                </ul>

            @elseif(Auth::guard('coffee_shop')->check())
                @php $cs = Auth::guard('coffee_shop')->user(); @endphp
                <div class="px-3 py-3 border-bottom border-dark d-flex align-items-center">
                    <div class="bg-primary text-secondary rounded-circle d-flex align-items-center justify-content-center mr-3 font-weight-bold" style="width: 42px; height: 42px; font-size: 18px; color: #33211D !important;">
                        <i class="fa fa-store"></i>
                    </div>
                    <div style="overflow: hidden;">
                        <div class="text-white font-weight-bold text-truncate" style="font-size: 14px;">{{ $cs->nama_toko }}</div>
                        <span class="badge badge-warning" style="font-size: 10px; background-color: #DA9F5B; color: #111;">
                            Buyer Mitra Roastery
                        </span>
                    </div>
                </div>

                <div class="sidebar-heading">Menu Coffee Shop</div>
                <ul class="nav flex-column mb-4">
                    <li class="nav-item">
                        <a href="{{ route('coffeeshop.dashboard') }}" class="nav-link {{ request()->routeIs('coffeeshop.dashboard') ? 'active' : '' }}">
                            <i class="fa fa-chart-pie"></i> Ringkasan Roastery
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('coffeeshop.pasokan.index') }}" class="nav-link {{ request()->routeIs('coffeeshop.pasokan.*') ? 'active' : '' }}">
                            <i class="fa fa-inbox"></i> Daftar Pasokan Kopi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('coffeeshop.produksi.diterima') }}" class="nav-link {{ request()->routeIs('coffeeshop.produksi.diterima') ? 'active' : '' }}">
                            <i class="fa fa-check-circle"></i> Produksi Diterima
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('coffeeshop.kemasan.index') }}" class="nav-link {{ request()->routeIs('coffeeshop.kemasan.*') ? 'active' : '' }}">
                            <i class="fa fa-qrcode"></i> Kemasan & Label QR
                        </a>
                    </li>
                </ul>

            @elseif(Auth::guard('web')->check())
                @php $admin = Auth::guard('web')->user(); @endphp
                <div class="px-3 py-3 border-bottom border-dark d-flex align-items-center">
                    <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center mr-3 font-weight-bold" style="width: 42px; height: 42px; font-size: 18px; color: #111 !important;">
                        <i class="fa fa-user-shield"></i>
                    </div>
                    <div style="overflow: hidden;">
                        <div class="text-white font-weight-bold text-truncate" style="font-size: 14px;">{{ $admin->name }}</div>
                        <span class="badge badge-warning" style="font-size: 10px; background-color: #DA9F5B; color: #111;">
                            Super Administrator
                        </span>
                    </div>
                </div>

                <div class="sidebar-heading">Menu Super Admin</div>
                <ul class="nav flex-column mb-4">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fa fa-chart-line"></i> Monitoring & Panen
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fa fa-users-cog"></i> Manajemen User
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.pesan.index') }}" class="nav-link {{ request()->routeIs('admin.pesan.*') ? 'active' : '' }}">
                            <i class="fa fa-envelope-open-text"></i> Pesan Masuk
                            @php
                                $unreadMsgCount = \App\Models\KontakPesan::where('is_read', false)->count();
                            @endphp
                            @if($unreadMsgCount > 0)
                                <span class="badge badge-warning float-right text-dark font-weight-bold" style="background-color: #DA9F5B;">
                                    {{ $unreadMsgCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.whatsapp.index') }}" class="nav-link {{ request()->routeIs('admin.whatsapp.*') ? 'active' : '' }}">
                            <i class="fab fa-whatsapp"></i> WhatsApp Gateway OTP
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.donasi.index') }}" class="nav-link {{ request()->routeIs('admin.donasi.*') ? 'active' : '' }}">
                            <i class="fa fa-hand-holding-heart"></i> Donasi & Kas Masuk/Keluar
                        </a>
                    </li>
                </ul>
            @endif


            <div class="sidebar-heading">Tautan Publik</div>
            <ul class="nav flex-column mb-5">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link" target="_blank">
                        <i class="fa fa-external-link-alt"></i> Halaman Publik
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('scan') }}" class="nav-link" target="_blank">
                        <i class="fa fa-camera"></i> Pemindai QR Publik
                    </a>
                </li>
                <li class="nav-item mt-3 px-3">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-block btn-sm text-left font-weight-bold">
                            <i class="fa fa-sign-out-alt mr-2"></i> Keluar (Logout)
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
        <!-- Sidebar Navigation End -->

        <!-- Content Wrapper Start -->
        <div id="content-wrapper">
            <!-- Topbar Start -->
            <header class="dashboard-topbar">
                <div class="d-flex align-items-center">
                    <button class="btn-toggle-sidebar mr-3" id="sidebarToggle" type="button">
                        <i class="fa fa-bars"></i>
                    </button>
                    <h5 class="m-0 font-weight-bold text-dark d-none d-sm-block">@yield('page_title', 'Dashboard')</h5>
                </div>
                <div class="d-flex align-items-center">
                    <span class="mr-3 text-muted small d-none d-md-inline">
                        <i class="fa fa-calendar-alt mr-1"></i> {{ date('d F Y') }}
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-dark font-weight-bold px-3">
                            <i class="fa fa-power-off text-danger mr-1"></i> Logout
                        </button>
                    </form>
                </div>
            </header>
            <!-- Topbar End -->

            <!-- Main Body Start -->
            <main class="p-3 p-md-4 flex-grow-1">
                @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" style="background-color: #DA9F5B; color: #111;">
                        <i class="fa fa-check-circle mr-2"></i> {{ session('status') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm">
                        <i class="fa fa-exclamation-triangle mr-2"></i> {{ session('warning') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0 pl-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                @yield('content')
            </main>
            <!-- Main Body End -->

            <!-- Footer -->
            <footer class="bg-white border-top py-3 px-4 text-center text-muted small">
                &copy; {{ date('Y') }} <strong>KopiLink Traceability System</strong> &bull; All Rights Reserved.
            </footer>
        </div>
        <!-- Content Wrapper End -->
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#sidebarToggle').on('click', function () {
                if ($(window).width() < 992) {
                    $('#sidebar').toggleClass('show');
                } else {
                    $('#sidebar').toggleClass('collapsed');
                    $('#content-wrapper').toggleClass('expanded');
                }
            });
        });

        // Mencegah double submit / data ganda jika tombol save diklik berkali-kali
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (form.dataset.submitting === 'true') {
                e.preventDefault();
                return false;
            }
            form.dataset.submitting = 'true';

            const submitBtns = form.querySelectorAll('button[type="submit"], input[type="submit"]');
            submitBtns.forEach(function (btn) {
                btn.disabled = true;
                if (btn.tagName === 'BUTTON') {
                    btn.setAttribute('data-original-html', btn.innerHTML);
                    btn.innerHTML = '<i class="fa fa-spinner fa-spin mr-1"></i> Menyimpan...';
                }
            });
        }, true);

        // Reset status submitting jika halaman di-load kembali (misal tombol back browser)
        window.addEventListener('pageshow', function (event) {
            document.querySelectorAll('form').forEach(function (form) {
                delete form.dataset.submitting;
                const submitBtns = form.querySelectorAll('button[type="submit"], input[type="submit"]');
                submitBtns.forEach(function (btn) {
                    btn.disabled = false;
                    const orig = btn.getAttribute('data-original-html');
                    if (orig) btn.innerHTML = orig;
                });
            });
            if (event.persisted || (window.performance && window.performance.navigation && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
