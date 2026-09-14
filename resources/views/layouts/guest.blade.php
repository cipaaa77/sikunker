<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ config('app.name', 'Sistem Penjadwalan Posyandu') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/logo_posyandu.png') }}">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8faf9;
            font-family: Arial, sans-serif;
        }

        .login-page {
            min-height: 100vh;
        }

        .login-left {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            width: 100%;
            max-width: 430px;
        }

        .login-logo {
            width: 90px;
            height: 90px;
            object-fit: contain;
            margin-bottom: 25px;
        }

        .login-title {
            color: #173f35;
            font-weight: 700;
        }

        .login-subtitle {
            color: #6c757d;
        }

        .form-control {
            height: 52px;
            border: 1px solid #d8dfdc;
            border-radius: 8px 0 0 8px;
        }

        .input-group-text {
            background: #fff;
            border: 1px solid #d8dfdc;
            border-left: 0;
            border-radius: 0 8px 8px 0;
            color: #718078;
        }

        .btn-login {
            height: 52px;
            background: #1f5c4d;
            border-color: #1f5c4d;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-login:hover {
            background: #17483c;
            border-color: #17483c;
        }

        .login-right {
            min-height: 100vh;
            padding: 18px;
        }

       .login-cover{
            height:100%;
            min-height:calc(100vh - 36px);
            border-radius:18px;
            background-image:
                linear-gradient(rgba(23,63,53,.25),rgba(23,63,53,.25)),
                url('{{ asset('storage/backcover3.png') }}');
            background-size:cover;
            background-position:center;
            background-repeat:no-repeat;
            display:flex;
            align-items:center;
            justify-content:center;
            text-align:center;
            padding:50px;
            color:white;
        }
                .login-cover h2 {
            font-weight: 700;
        }

        .login-cover p {
            max-width: 500px;
            margin: 15px auto 0;
            opacity: .9;
        }

        @media (max-width: 991px) {
            .login-left {
                min-height: 100vh;
            }
        }
    </style>
</head>

<body>

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.querySelectorAll('.showpass').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = this.closest('.input-group').querySelector('input');

                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                }
            });
        });
    </script>

</body>

</html>