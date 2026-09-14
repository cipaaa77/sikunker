<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Dashboard') - Sistem Penjadwalan Posyandu</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/logo_posyandu.png') }}">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root{
            --jade-dark:#174a43;
            --jade:#21665c;
            --jade-light:#e8f2f0;
            --jade-hover:#f0f6f5;
            --text-dark:#243746;
            --text-muted:#78909c;
            --border:#e1e7e9;
            --bg:#f5f7f8;
        }

        *{box-sizing:border-box}

        html,body{
            margin:0;
            min-height:100%;
        }

        body{
            background:var(--bg);
            color:var(--text-dark);
            font-family:Arial,Helvetica,sans-serif;
            font-size:14px;
        }

        a{text-decoration:none}

        /* GLOBAL BACKGROUND */
        .app-background{
            position:fixed;
            top:0;
            left:0;
            width:100%;
            height:360px;
            z-index:0;
            pointer-events:none;
            background-image:url('{{ asset('storage/backcover2.png') }}');
            background-size:cover;
            background-position:center 0%;
            background-repeat:no-repeat;
        }

        .app-background::after{
            content:"";
            position:absolute;
            left:0;
            right:0;
            bottom:0;
            height:70px;
            background:linear-gradient(
                to bottom,
                rgba(245,247,248,0),
                var(--bg)
            );
        }

        /* SIDEBAR */
        .sidebar{
            position:fixed;
            top:12px;
            left:12px;
            bottom:12px;
            width:250px;
            background:#fff;
            border:1px solid var(--border);
            border-radius:14px;
            z-index:1040;
            overflow-y:auto;
            transition:transform .3s ease;
            box-shadow:0 4px 18px rgba(30,50,55,.06);
        }

        .sidebar-brand{
            height:76px;
            padding:14px 18px;
            border-bottom:1px solid var(--border);
            display:flex;
            align-items:center;
            gap:11px;
        }

        .brand-logo{
            width:43px;
            height:43px;
            border-radius:9px;
            object-fit:contain;
            flex-shrink:0;
            background:#fff;
        }

        .brand-logo-placeholder{
            width:43px;
            height:43px;
            border-radius:9px;
            background:var(--jade-light);
            color:var(--jade-dark);
            display:flex;
            align-items:center;
            justify-content:center;
            flex-shrink:0;
            font-size:19px;
        }

        .sidebar-brand-text{
            min-width:0;
        }

        .sidebar-brand-title{
            color:var(--jade-dark);
            font-size:16px;
            font-weight:700;
            line-height:1.2;
            white-space:nowrap;
        }

        .sidebar-brand-subtitle{
            color:var(--text-muted);
            font-size:9.5px;
            line-height:1.3;
            margin-top:3px;
        }

        .sidebar-menu{
            padding:18px 12px;
        }

        .menu-title{
            color:#9aa8ae;
            font-size:10px;
            font-weight:700;
            letter-spacing:.7px;
            text-transform:uppercase;
            padding:0 12px 8px;
        }

        .sidebar-menu a{
            display:flex;
            align-items:center;
            gap:12px;
            padding:10px 12px;
            margin-bottom:3px;
            border-radius:8px;
            color:#5d6d74;
            font-size:13px;
            font-weight:500;
            transition:.2s ease;
        }

        .sidebar-menu a i{
            width:18px;
            text-align:center;
            font-size:14px;
        }

        .sidebar-menu a:hover{
            color:var(--jade-dark);
            background:var(--jade-hover);
        }

        .sidebar-menu a.active{
            color:#fff;
            background:var(--jade-dark);
        }

        .sidebar-menu a.active i{
            color:#fff;
        }

        .sidebar-divider{
            height:1px;
            background:var(--border);
            margin:14px 12px;
        }

        /* MAIN */
        .main-wrapper{
            position:relative;
            z-index:1;
            margin-left:274px;
            min-height:100vh;
            display:flex;
            flex-direction:column;
        }

        /* TOPBAR */
        .top-navbar{
            min-height:72px;
            margin-right:12px;
            padding:0 20px 0 24px;
            background:transparent;
            display:flex;
            align-items:center;
            justify-content:space-between;
        }

        .topbar-brand{
            display:flex;
            align-items:center;
            gap:12px;
            min-width:0;
        }

        .topbar-brand-text{
            display:flex;
            flex-direction:column;
            justify-content:center;
        }

        .top-navbar-title{
            color:#fff;
            font-size:16px;
            font-weight:700;
            line-height:1.2;
            text-shadow:0 1px 3px rgba(0,0,0,.25);
        }

        .top-navbar-subtitle{
            color:rgba(255,255,255,.9);
            font-size:11px;
            margin-top:4px;
            text-shadow:0 1px 3px rgba(0,0,0,.2);
        }

        .mobile-menu-button{
            display:none;
            border:0;
            background:transparent;
            color:#fff;
            font-size:19px;
            padding:4px;
        }

        /* PROFILE */
        .profile-dropdown{
            position:relative;
        }

        .profile-button{
            min-height:44px;
            border:1px solid rgba(255,255,255,.25);
            background:rgba(255,255,255,.16);
            color:#fff;
            display:flex;
            align-items:center;
            gap:9px;
            padding:5px 10px;
            border-radius:9px;
            transition:.2s ease;
            backdrop-filter:blur(5px);
        }

        .profile-button:hover,
        .profile-button.show{
            background:rgba(255,255,255,.25);
            color:#fff;
        }

        .user-avatar{
            width:32px;
            height:32px;
            border-radius:50%;
            background:rgba(255,255,255,.18);
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:13px;
        }

        .user-info{
            text-align:left;
            line-height:1.2;
        }

        .user-name{
            color:#fff;
            font-size:12px;
            font-weight:600;
        }

        .user-role{
            color:rgba(255,255,255,.75);
            font-size:9px;
            margin-top:3px;
            text-transform:capitalize;
        }

        .profile-arrow{
            margin-left:3px;
            font-size:9px;
            transition:transform .2s ease;
        }

        .profile-button.show .profile-arrow{
            transform:rotate(180deg);
        }

        .profile-menu{
            min-width:210px;
            padding:8px;
            margin-top:8px!important;
            border:1px solid var(--border);
            border-radius:10px;
            box-shadow:0 8px 25px rgba(30,50,55,.12);
        }

        .profile-menu-header{
            padding:10px 11px;
            border-bottom:1px solid var(--border);
            margin-bottom:5px;
        }

        .profile-menu-name{
            color:var(--text-dark);
            font-size:13px;
            font-weight:600;
        }

        .profile-menu-email{
            color:var(--text-muted);
            font-size:11px;
            margin-top:3px;
            word-break:break-word;
        }

        .profile-menu .dropdown-item{
            padding:9px 11px;
            border-radius:7px;
            color:#5d6d74;
            font-size:13px;
        }

        .profile-menu .dropdown-item i{
            width:20px;
            color:var(--jade);
        }

        .profile-menu .dropdown-item:hover{
            color:var(--jade-dark);
            background:var(--jade-hover);
        }

        .profile-menu .logout-item:hover{
            color:#b33a3a;
            background:#fff3f3;
        }

        .profile-menu .logout-item i{
            color:#b33a3a;
        }

        /* CONTENT */
        .content-wrapper{
            padding:24px;
            flex:1;
            position:relative;
        }

        .page-header{
            margin-bottom:20px;
        }

        .breadcrumb{
            margin-bottom:8px;
            font-size:12px;
        }

        .breadcrumb-item a{
            color:var(--jade);
        }

        .breadcrumb-item.active{
            color:var(--text-muted);
        }

        .page-title{
            color:var(--text-dark);
            font-size:23px;
            font-weight:700;
            margin-bottom:4px;
        }

        .page-description{
            color:var(--text-muted);
            margin:0;
            font-size:13px;
        }

        /* CARD */
        .card{
            border:1px solid var(--border);
            border-radius:12px;
            box-shadow:0 2px 10px rgba(30,50,55,.04);
        }

        .card-header{
            background:#fff;
            border-bottom:1px solid var(--border);
            border-radius:12px 12px 0 0!important;
        }

        /* BUTTON */
        .btn-jade{
            background:var(--jade-dark);
            border-color:var(--jade-dark);
            color:#fff;
        }

        .btn-jade:hover,
        .btn-jade:focus{
            background:var(--jade);
            border-color:var(--jade);
            color:#fff;
        }

        /* FORM */
        .form-label{
            color:#52636a;
            font-size:13px;
            font-weight:600;
            margin-bottom:6px;
        }

        .form-control,
        .form-select{
            border-color:#d8e0e2;
            border-radius:7px;
            font-size:13px;
            min-height:40px;
        }

        .form-control:focus,
        .form-select:focus{
            border-color:var(--jade);
            box-shadow:0 0 0 .15rem rgba(33,102,92,.12);
        }

        /* TABLE */
        .table{
            margin-bottom:0;
            font-size:13px;
        }

        .table thead th{
            background:#f8fafb;
            color:#61747c;
            font-size:11px;
            font-weight:700;
            letter-spacing:.4px;
            text-transform:uppercase;
            white-space:nowrap;
            padding:13px 12px;
            border-bottom:1px solid var(--border);
        }

        .table tbody td{
            padding:13px 12px;
            vertical-align:middle;
            border-color:#edf0f1;
        }

        .table tbody tr:hover{
            background:#fbfcfc;
        }

        .action-btn{
            width:32px;
            height:32px;
            padding:0;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            border-radius:6px;
        }

        .badge-active{
            background:#dff1e9;
            color:#24624f;
            font-weight:600;
        }

        .badge-inactive{
            background:#f0f1f2;
            color:#69777d;
            font-weight:600;
        }

        /* PAGINATION */
        .pagination{
            margin-bottom:0;
        }

        .pagination .page-link{
            color:var(--jade-dark);
            border-color:var(--border);
            font-size:13px;
            min-width:36px;
            text-align:center;
        }

        .pagination .page-link:hover{
            color:var(--jade-dark);
            background:var(--jade-hover);
            border-color:#cbdad7;
        }

        .pagination .page-item.active .page-link{
            background:var(--jade-dark);
            border-color:var(--jade-dark);
            color:#fff;
        }

        .pagination .page-item.disabled .page-link{
            color:#a3afb4;
            background:#f8fafb;
            border-color:var(--border);
        }

        /* ALERT */
        .page-alert{
            position:relative;
            overflow:hidden;
            border-radius:7px;
            margin-bottom:18px;
            padding:14px 46px 14px 18px;
            border:1px solid transparent;
            animation:alertEnter .45s ease forwards;
        }

        .page-alert.alert-success{
            background:#dceee7;
            border-color:#acd4c5;
            color:#174a43;
        }

        .page-alert.alert-danger{
            background:#f7dddd;
            border-color:#e5b4b4;
            color:#8b3030;
        }

        .page-alert.alert-warning{
            background:#fff0d1;
            border-color:#eed29a;
            color:#785b1d;
        }

        .page-alert.alert-info{
            background:#dceef3;
            border-color:#afd5df;
            color:#245d6b;
        }

        .page-alert-content{
            display:flex;
            align-items:center;
            gap:12px;
        }

        .page-alert-icon{
            font-size:15px;
            flex-shrink:0;
        }

        .page-alert-message{
            font-size:14px;
            font-weight:500;
            line-height:1.4;
        }

        .page-alert-close{
            position:absolute;
            top:50%;
            right:14px;
            transform:translateY(-50%);
            border:0;
            background:transparent;
            color:currentColor;
            opacity:.65;
            font-size:21px;
            line-height:1;
            padding:2px 5px;
            cursor:pointer;
        }

        .page-alert-close:hover{
            opacity:1;
        }

        .page-alert-progress{
            position:absolute;
            left:0;
            bottom:0;
            height:3px;
            width:100%;
            background:rgba(23,74,67,.22);
            transform-origin:left;
            animation:alertProgress 3.8s linear forwards;
        }

        .page-alert.is-closing{
            animation:alertExit .4s ease forwards;
        }

        @keyframes alertEnter{
            from{opacity:0;transform:translateY(-18px)}
            to{opacity:1;transform:translateY(0)}
        }

        @keyframes alertExit{
            from{
                opacity:1;
                transform:translateY(0);
                max-height:100px;
                margin-bottom:18px
            }
            to{
                opacity:0;
                transform:translateY(-12px);
                max-height:0;
                margin-bottom:0;
                padding-top:0;
                padding-bottom:0
            }
        }

        @keyframes alertProgress{
            from{transform:scaleX(1)}
            to{transform:scaleX(0)}
        }

        /* FOOTER */
        .app-footer{
            margin:0 12px 12px 0;
            padding:14px 24px;
            border-top:1px solid var(--border);
            color:var(--text-muted);
            font-size:11px;
            text-align:center;
            background:transparent;
        }

        .app-footer strong{
            color:var(--jade-dark);
            font-weight:600;
        }

        /* SWEET ALERT */
        .swal2-popup{
            border-radius:12px!important;
        }

        .swal2-confirm{
            background:var(--jade-dark)!important;
        }

        /* MOBILE */
        .sidebar-overlay{
            display:none;
        }

        @media(max-width:991.98px){
            .app-background{
                height:23vh;
                min-height:165px;
            }

            .sidebar{
                transform:translateX(-280px);
            }

            .sidebar.show{
                transform:translateX(0);
            }

            .main-wrapper{
                margin-left:0;
            }

            .top-navbar{
                margin-right:0;
                padding:0 18px;
            }

            .mobile-menu-button{
                display:inline-block;
            }

            .sidebar-overlay{
                position:fixed;
                inset:0;
                background:rgba(0,0,0,.25);
                z-index:1035;
            }

            .sidebar-overlay.show{
                display:block;
            }

            .content-wrapper{
                padding:18px;
            }

            .app-footer{
                margin-right:0;
            }
        }

        @media(max-width:575.98px){
            .app-background{
                height:20vh;
                min-height:145px;
            }

            .top-navbar{
                padding:0 14px;
            }

            .user-info,
            .profile-arrow{
                display:none;
            }

            .content-wrapper{
                padding:14px;
            }

            .page-title{
                font-size:20px;
            }

            .page-alert{
                margin-bottom:14px;
            }

            .profile-menu{
                position:absolute;
                right:0;
            }

            .app-footer{
                padding:13px 14px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- BACKGROUND GLOBAL --}}
    <div class="app-background"></div>

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('storage/logo_posyandu.png') }}" alt="Logo Posyandu" class="brand-logo">

            <div class="sidebar-brand-text">
                <div class="sidebar-brand-title">Posyandu Syifa</div>
                <div class="sidebar-brand-subtitle">Sistem Penjadwalan</div>
            </div>
        </div>
        </div>

        <nav class="sidebar-menu">
            <div class="menu-title">Utama</div>

            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>

            <div class="menu-title mt-4">Master Data</div>

            <a href="{{ route('wilayah.index') }}" class="{{ request()->routeIs('wilayah.*') ? 'active' : '' }}">
                <i class="fas fa-map-location-dot"></i>
                <span>Wilayah</span>
            </a>

            <a href="{{ route('posyandu.index') }}" class="{{ request()->routeIs('posyandu.*') ? 'active' : '' }}">
                <i class="fas fa-house-medical"></i>
                <span>Posyandu</span>
            </a>

            <a href="{{ route('kegiatan.index') }}" class="{{ request()->routeIs('kegiatan.*') ? 'active' : '' }}">
                <i class="fas fa-list-check"></i>
                <span>Kegiatan</span>
            </a>

            <a href="{{ route('hari-operasional.index') }}" class="{{ request()->routeIs('hari-operasional.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-days"></i>
                <span>Hari Operasional</span>
            </a>

   @if(auth()->check() && auth()->user()->role === 'admin')
    <div class="menu-title mt-4">Administrasi</div>

    <a href="{{ route('admin.users.index') }}"
       class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="fas fa-users-cog"></i>
        <span>Master User</span>
    </a>
@endif

            <div class="menu-title mt-4">Transaksi</div>

            <a href="{{ route('jadwal.index') }}" class="{{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i>
                <span>Jadwal Bulanan</span>
            </a>
       

            <div class="menu-title mt-4">Laporan</div>

            <a href="{{ route('laporan-jadwal.index') }}"
            class="{{ request()->routeIs('laporan-jadwal.*') ? 'active' : '' }}">
                <i class="fas fa-file-lines"></i>
                <span>Laporan Jadwal</span>
            </a>

            <a href="#">
                <i class="fas fa-clock-rotate-left"></i>
                <span>Riwayat Jadwal</span>
            </a>

            <div class="sidebar-divider"></div>
        </nav>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- MAIN --}}
    <div class="main-wrapper">

        {{-- TOPBAR --}}
        <header class="top-navbar">
            <div class="topbar-brand">
                <button type="button" class="mobile-menu-button" id="mobileMenuButton">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="topbar-brand-text">
                    <div class="top-navbar-title">Sistem Penjadwalan Posyandu</div>
                    <div class="top-navbar-subtitle">Manajemen jadwal kegiatan Posyandu</div>
                </div>
            </div>

            @auth
                <div class="dropdown profile-dropdown">
                    <button type="button" class="profile-button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>

                        <div class="user-info">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">{{ auth()->user()->role }}</div>
                        </div>

                        <i class="fas fa-chevron-down profile-arrow"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end profile-menu">
                        <li>
                            <div class="profile-menu-header">
                                <div class="profile-menu-name">{{ auth()->user()->name }}</div>
                                <div class="profile-menu-email">{{ auth()->user()->email }}</div>
                            </div>
                        </li>

                        <li>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-user"></i>
                                Profil
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                                @csrf
                                <button type="submit" class="dropdown-item logout-item">
                                    <i class="fas fa-right-from-bracket"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </header>

        {{-- CONTENT --}}
        <main class="content-wrapper">

            @if(session('success'))
                <div class="page-alert alert-success" role="alert">
                    <div class="page-alert-content">
                        <i class="fas fa-circle-check page-alert-icon"></i>
                        <div class="page-alert-message">{{ session('success') }}</div>
                    </div>
                    <button type="button" class="page-alert-close" aria-label="Tutup">&times;</button>
                    <div class="page-alert-progress"></div>
                </div>
            @endif

            @if(session('error'))
                <div class="page-alert alert-danger" role="alert">
                    <div class="page-alert-content">
                        <i class="fas fa-circle-xmark page-alert-icon"></i>
                        <div class="page-alert-message">{{ session('error') }}</div>
                    </div>
                    <button type="button" class="page-alert-close" aria-label="Tutup">&times;</button>
                    <div class="page-alert-progress"></div>
                </div>
            @endif

            @if(session('warning'))
                <div class="page-alert alert-warning" role="alert">
                    <div class="page-alert-content">
                        <i class="fas fa-triangle-exclamation page-alert-icon"></i>
                        <div class="page-alert-message">{{ session('warning') }}</div>
                    </div>
                    <button type="button" class="page-alert-close" aria-label="Tutup">&times;</button>
                    <div class="page-alert-progress"></div>
                </div>
            @endif

            @if(session('info'))
                <div class="page-alert alert-info" role="alert">
                    <div class="page-alert-content">
                        <i class="fas fa-circle-info page-alert-icon"></i>
                        <div class="page-alert-message">{{ session('info') }}</div>
                    </div>
                    <button type="button" class="page-alert-close" aria-label="Tutup">&times;</button>
                    <div class="page-alert-progress"></div>
                </div>
            @endif

            @if($errors->any())
                <div class="page-alert alert-danger" role="alert">
                    <div class="page-alert-content">
                        <i class="fas fa-circle-exclamation page-alert-icon"></i>
                        <div>
                            <div class="page-alert-message mb-1">Terdapat kesalahan pada data.</div>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <button type="button" class="page-alert-close" aria-label="Tutup">&times;</button>
                </div>
            @endif

            @yield('content')

        </main>

        <footer class="app-footer">
            Copyright © 2026
            <strong>Rafi</strong> •
            <strong>Syifa</strong> •
            <strong>Bilqis</strong> •
            <strong>Linda</strong> •
            <strong>Fitri</strong>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded',function(){
            document.querySelectorAll('.page-alert').forEach(function(alert){
                const closeButton=alert.querySelector('.page-alert-close');
                const timer=setTimeout(function(){closeAlert(alert)},3800);

                if(closeButton){
                    closeButton.addEventListener('click',function(){
                        clearTimeout(timer);
                        closeAlert(alert);
                    });
                }
            });

            function closeAlert(alert){
                if(alert.classList.contains('is-closing'))return;
                alert.classList.add('is-closing');

                setTimeout(function(){
                    alert.remove();
                },400);
            }

            document.querySelectorAll('.confirm-submit').forEach(function(form){
                form.addEventListener('submit',function(event){
                    if(form.dataset.confirmed==='true')return;

                    event.preventDefault();

                    let title='Simpan Data?';
                    let confirmText='Ya, Simpan';

                    if(form.dataset.action==='update'){
                        title='Simpan Perubahan?';
                        confirmText='Ya, Perbarui';
                    }

                    Swal.fire({
                        title:title,
                        text:form.dataset.message||'Data akan disimpan ke dalam sistem.',
                        icon:'question',
                        showCancelButton:true,
                        confirmButtonText:confirmText,
                        cancelButtonText:'Batal',
                        reverseButtons:true
                    }).then(function(result){
                        if(result.isConfirmed){
                            form.dataset.confirmed='true';

                            Swal.fire({
                                title:'Memproses...',
                                text:'Mohon tunggu.',
                                allowOutsideClick:false,
                                allowEscapeKey:false,
                                showConfirmButton:false,
                                didOpen:function(){
                                    Swal.showLoading();
                                }
                            });

                            form.submit();
                        }
                    });
                });
            });

            document.querySelectorAll('.delete-form').forEach(function(form){
                form.addEventListener('submit',function(event){
                    if(form.dataset.confirmed==='true')return;

                    event.preventDefault();

                    const name=form.dataset.name||'data ini';

                    Swal.fire({
                        title:'Hapus Data?',
                        html:'Data <strong>'+name+'</strong> akan dihapus dari sistem.',
                        icon:'warning',
                        showCancelButton:true,
                        confirmButtonText:'Ya, Hapus',
                        cancelButtonText:'Batal',
                        confirmButtonColor:'#b33a3a',
                        reverseButtons:true
                    }).then(function(result){
                        if(result.isConfirmed){
                            form.dataset.confirmed='true';

                            Swal.fire({
                                title:'Menghapus...',
                                text:'Mohon tunggu.',
                                allowOutsideClick:false,
                                allowEscapeKey:false,
                                showConfirmButton:false,
                                didOpen:function(){
                                    Swal.showLoading();
                                }
                            });

                            form.submit();
                        }
                    });
                });
            });

            document.querySelectorAll('.logout-form').forEach(function(form){
                form.addEventListener('submit',function(event){
                    if(form.dataset.confirmed==='true')return;

                    event.preventDefault();

                    Swal.fire({
                        title:'Logout?',
                        text:'Anda akan keluar dari sistem.',
                        icon:'question',
                        showCancelButton:true,
                        confirmButtonText:'Ya, Logout',
                        cancelButtonText:'Batal',
                        reverseButtons:true
                    }).then(function(result){
                        if(result.isConfirmed){
                            form.dataset.confirmed='true';
                            form.submit();
                        }
                    });
                });
            });

            const mobileButton=document.getElementById('mobileMenuButton');
            const sidebar=document.getElementById('sidebar');
            const overlay=document.getElementById('sidebarOverlay');

            if(mobileButton){
                mobileButton.addEventListener('click',function(){
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                });
            }

            if(overlay){
                overlay.addEventListener('click',function(){
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }

            document.querySelectorAll('.sidebar-menu a').forEach(function(link){
                link.addEventListener('click',function(){
                    if(window.innerWidth<=991){
                        sidebar.classList.remove('show');
                        overlay.classList.remove('show');
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>