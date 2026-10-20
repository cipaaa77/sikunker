
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Beranda') - {{ config('app.name', 'Sistem Penjadwalan Posyandu') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/logo_posyandu.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --public-jade-dark: #173f35;
            --public-jade: #1f5c4d;
            --public-jade-hover: #17483c;
            --public-jade-light: #e8f2ee;
            --public-text: #243746;
            --public-muted: #718078;
            --public-border: #e2e9e6;
            --public-bg: #f8faf9;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 90px;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: var(--public-bg);
            color: var(--public-text);
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
        }

        a {
            text-decoration: none;
        }

        /* NAVBAR */
        .public-navbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            min-height: 76px;
            background: rgba(255, 255, 255, .97);
            border-bottom: 1px solid var(--public-border);
            box-shadow: 0 3px 14px rgba(23, 63, 53, .035);
            backdrop-filter: blur(12px);
        }

        .public-navbar .container {
            min-height: 76px;
        }

        .public-brand {
            display: inline-flex;
            align-items: center;
            gap: 11px;
            min-width: 0;
        }

        .public-logo {
            display: block;
            width: 46px;
            height: 46px;
            flex-shrink: 0;
            object-fit: contain;
            border-radius: 9px;
            background: #fff;
        }

        .public-brand-text {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .public-brand-title {
            color: var(--public-jade-dark);
            font-size: 15px;
            font-weight: 750;
            line-height: 1.2;
        }

        .public-brand-subtitle {
            color: var(--public-muted);
            font-size: 10px;
        }

        .public-nav-links {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .public-nav-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 40px;
            padding: 9px 13px;
            border-radius: 7px;
            color: #5d6d65;
            font-size: 12px;
            font-weight: 600;
            transition: .2s ease;
        }

        .public-nav-link:hover,
        .public-nav-link.active {
            background: var(--public-jade-light);
            color: var(--public-jade-dark);
        }

        .public-nav-cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 40px;
            margin-left: 7px;
            padding: 10px 15px;
            border: 1px solid var(--public-jade);
            border-radius: 8px;
            background: var(--public-jade);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            transition: .2s ease;
        }

        .public-nav-cta:hover {
            border-color: var(--public-jade-hover);
            background: var(--public-jade-hover);
            color: #fff;
            transform: translateY(-1px);
        }

        .public-navbar-toggler {
            width: 40px;
            height: 40px;
            border: 1px solid var(--public-border);
            border-radius: 8px;
            background: #fff;
            color: var(--public-jade-dark);
        }

        .public-navbar-toggler:focus {
            box-shadow: 0 0 0 .15rem rgba(31, 92, 77, .12);
        }

        /* MAIN */
        .public-main {
            flex: 1;
            min-width: 0;
        }

        /* GENERAL BUTTON */
        .btn-public {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 10px 15px;
            border: 1px solid var(--public-jade);
            border-radius: 8px;
            background: var(--public-jade);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            transition: .2s ease;
        }

        .btn-public:hover,
        .btn-public:focus {
            border-color: var(--public-jade-hover);
            background: var(--public-jade-hover);
            color: #fff;
        }

        .btn-public-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 10px 15px;
            border: 1px solid #cbded5;
            border-radius: 8px;
            background: #fff;
            color: var(--public-jade-dark);
            font-size: 12px;
            font-weight: 700;
            transition: .2s ease;
        }

        .btn-public-outline:hover {
            background: var(--public-jade-light);
            color: var(--public-jade-dark);
        }

        /* FOOTER */
        .public-footer {
            margin-top: 45px;
            padding: 27px 0;
            border-top: 1px solid var(--public-border);
            background: #fff;
        }

        .public-footer-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 9px;
        }

        .public-footer-logo {
            width: 34px;
            height: 34px;
            object-fit: contain;
        }

        .public-footer-title {
            margin: 0;
            color: var(--public-jade-dark);
            font-size: 13px;
            font-weight: 750;
        }

        .public-footer-description {
            max-width: 430px;
            margin-bottom: 0;
            color: var(--public-muted);
            font-size: 11px;
            line-height: 1.8;
        }

        .public-footer-copy {
            color: var(--public-muted);
            font-size: 11px;
            line-height: 1.8;
        }

        .public-footer-copy strong {
            color: var(--public-jade-dark);
            font-weight: 700;
        }

        /* RESPONSIVE */
        @media (max-width: 991.98px) {
            .public-navbar .container {
                padding-top: 10px;
                padding-bottom: 10px;
            }

            .public-nav-links {
                align-items: stretch;
                gap: 4px;
                padding: 12px 0 5px;
            }

            .public-nav-link {
                justify-content: flex-start;
                padding: 11px 12px;
            }

            .public-nav-cta {
                justify-content: center;
                margin: 5px 0 0;
            }
        }

        @media (max-width: 575.98px) {
            .public-logo {
                width: 40px;
                height: 40px;
            }

            .public-brand-title {
                font-size: 13px;
            }

            .public-brand-subtitle {
                font-size: 9px;
            }

            .public-footer {
                margin-top: 30px;
                padding: 22px 0;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- NAVBAR PUBLIK --}}
    <nav class="navbar navbar-expand-lg public-navbar">
        <div class="container">

            <a href="{{ route('public.landing') }}"
               class="public-brand"
               aria-label="Beranda Sistem Penjadwalan Posyandu">

                <img
                    src="{{ asset('storage/logo_posyandu.png') }}"
                    alt="Logo Posyandu"
                    class="public-logo"
                >

                <span class="public-brand-text">
                    <span class="public-brand-title">Posyandu</span>
                    <span class="public-brand-subtitle">
                        Sistem Informasi Penjadwalan
                    </span>
                </span>
            </a>

            <button
                class="navbar-toggler public-navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#publicNavbarMenu"
                aria-controls="publicNavbarMenu"
                aria-expanded="false"
                aria-label="Buka navigasi"
            >
                <i class="fas fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="publicNavbarMenu">
                <div class="public-nav-links ms-auto">

                    <a
                        href="{{ route('public.landing') }}"
                        class="public-nav-link {{ request()->routeIs('public.landing') ? 'active' : '' }}"
                    >
                        <i class="fas fa-house"></i>
                        Beranda
                    </a>
                </div>
            </div>

        </div>
    </nav>

    {{-- KONTEN HALAMAN PUBLIK --}}
    <main class="public-main">
        @yield('content')
    </main>

    {{-- FOOTER PUBLIK --}}
    <footer class="public-footer">
        <div class="container">
            <div class="row align-items-center g-3">

                <div class="col-md-7">
                    <div class="public-footer-brand">
                        <img
                            src="{{ asset('storage/logo_posyandu.png') }}"
                            alt="Logo Posyandu"
                            class="public-footer-logo"
                        >

                        <p class="public-footer-title">
                            Sistem Penjadwalan Posyandu
                        </p>
                    </div>

                    <p class="public-footer-description">
                        Media informasi jadwal kegiatan Posyandu untuk
                        membantu masyarakat mengetahui waktu dan lokasi
                        pelayanan yang telah dijadwalkan.
                    </p>
                </div>

                <div class="col-md-5 text-md-end">
                    <div class="public-footer-copy">
                        Copyright &copy; {{ date('Y') }}<br>
                        <strong>Syifa, Fitri, Rafi, Bilqis, Linda</strong>
                    </div>
                </div>

            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
