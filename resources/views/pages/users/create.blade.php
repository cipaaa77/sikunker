@extends('layouts.app')

@section('title', 'Tambah User')

@push('styles')
<style>
    .user-create-page .page-card {
        border-radius: 12px;
        border: 1px solid #edf0f0;
    }

    .user-create-page .page-card-header {
        padding: 18px 20px;
    }

    .user-create-page .page-title-small {
        color: #344767;
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .user-create-page .page-description {
        color: #8898aa;
        font-size: 12px;
        margin-bottom: 0;
    }

    .user-create-page .btn-jade {
        background: #174a43;
        border-color: #174a43;
        color: #ffffff;
    }

    .user-create-page .btn-jade:hover,
    .user-create-page .btn-jade:focus {
        background: #123d37;
        border-color: #123d37;
        color: #ffffff;
    }

    .user-create-page .form-label {
        color: #344767;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .user-create-page .form-control,
    .user-create-page .form-select {
        border-color: #dfe5e7;
        font-size: 13px;
        border-radius: 7px;
    }

    .user-create-page .form-control:focus,
    .user-create-page .form-select:focus {
        border-color: #21665c;
        box-shadow: 0 0 0 0.15rem rgba(33, 102, 92, 0.10);
    }

    .user-create-page .form-text {
        color: #8898aa;
        font-size: 11px;
    }
</style>
@endpush

@section('content')
<div class="user-create-page">
    <div class="row">
        <div class="col-lg-8 col-xl-7">
            <div class="card page-card">
                <div class="page-card-header">
                    <div class="page-title-small">
                        Tambah User
                    </div>

                    <p class="page-description">
                        Lengkapi informasi pengguna baru dengan benar.
                    </p>
                </div>

                <div class="border-top"></div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Nama
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Masukkan nama pengguna"
                                required
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="Contoh: user@email.com"
                                required
                            >

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">
                                Role
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="role"
                                id="role"
                                class="form-select @error('role') is-invalid @enderror"
                                required
                            >
                                <option value="">
                                    Pilih role
                                </option>

                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                    Admin
                                </option>

                                <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>
                                    Petugas
                                </option>

                                <option value="koordinator" {{ old('role') == 'koordinator' ? 'selected' : '' }}>
                                    Koordinator
                                </option>
                            </select>

                            @error('role')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Masukkan password"
                            >

                            <div class="form-text">
                                Kosongkan jika menggunakan password default: <strong>password</strong>.
                            </div>

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>
                                Kembali
                            </a>

                            <button type="submit" class="btn btn-jade btn-sm">
                                <i class="fas fa-save me-1"></i>
                                Simpan User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection