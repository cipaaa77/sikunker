@extends('layouts.guest')

@section('content')

<div class="container-fluid">
    <div class="row login-page">

        {{-- FORM LOGIN --}}
        <div class="col-lg-6 login-left">
            <div class="login-box">

                {{-- LOGO --}}
                <div class="text-center">
                    <img
                        src="{{ asset('storage/logo_posyandu.png') }}"
                        class="login-logo"
                        alt="Logo Posyandu"
                    >
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">

                        <div class="mb-4">
                            <h3 class="login-title mb-2">
                                Selamat Datang
                            </h3>

                            <p class="login-subtitle mb-0">
                                Masukkan email dan password untuk masuk
                                ke sistem.
                            </p>
                        </div>

                        {{-- ALERT --}}
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form
                            method="POST"
                            action="{{ route('login.process') }}"
                        >
                            @csrf

                            {{-- EMAIL --}}
                            <div class="mb-3">
                                <label class="form-label">
                                    Email
                                </label>

                                <div class="input-group">
                                    <input
                                        type="email"
                                        name="email"
                                        class="form-control"
                                        placeholder="Masukkan email"
                                        value="{{ old('email') }}"
                                        autofocus
                                    >

                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>

                                @error('email')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- PASSWORD --}}
                            <div class="mb-3">
                                <label class="form-label">
                                    Password
                                </label>

                                <div class="input-group">
                                    <input
                                        type="password"
                                        name="password"
                                        class="form-control"
                                        placeholder="Masukkan password"
                                    >

                                    <span class="input-group-text">
                                        <i
                                            class="fas fa-eye showpass"
                                            style="cursor:pointer"
                                        ></i>
                                    </span>
                                </div>

                                @error('password')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- BUTTON --}}
                            <button
                                type="submit"
                                class="btn btn-login text-white w-100 mt-3"
                            >
                                <i class="fas fa-right-to-bracket me-2"></i>
                                Masuk
                            </button>

                        </form>

                    </div>
                </div>

                <div class="text-center mt-4 text-muted small">
                    © {{ date('Y') }} Sistem Penjadwalan Posyandu
                </div>

            </div>
        </div>


        {{-- COVER KANAN --}}
        <div class="col-lg-6 d-none d-lg-block login-right">

            <div class="login-cover">

                <div>

                    <h2>
                        Sistem Penjadwalan Posyandu
                    </h2>

                    <p>
                        Kota bla bla bla
                    </p>
                </div>

            </div>

        </div>

    </div>
</div>

@endsection