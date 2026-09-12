@extends('layouts.app')

@section('title', 'Edit User')

@push('styles')
<style>
    .user-edit-page .page-card {
        border-radius: 12px;
        border: 1px solid #edf0f0;
    }

    .user-edit-page .page-card-header {
        padding: 18px 20px;
    }

    .user-edit-page .page-title-small {
        color: #344767;
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .user-edit-page .page-description {
        color: #8898aa;
        font-size: 12px;
        margin-bottom: 0;
    }

    .user-edit-page .btn-jade {
        background: #174a43;
        border-color: #174a43;
        color: #ffffff;
    }

    .user-edit-page .btn-jade:hover,
    .user-edit-page .btn-jade:focus {
        background: #123d37;
        border-color: #123d37;
        color: #ffffff;
    }

    .user-edit-page .form-label {
        color: #344767;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .user-edit-page .form-control,
    .user-edit-page .form-select {
        border-color: #dfe5e7;
        font-size: 13px;
        border-radius: 7px;
    }

    .user-edit-page .form-control:focus,
    .user-edit-page .form-select:focus {
        border-color: #21665c;
        box-shadow: 0 0 0 0.15rem rgba(33, 102, 92, 0.10);
    }

    .user-edit-page .form-text {
        color: #8898aa;
        font-size: 11px;
    }

    .user-edit-page .info-box {
        background: #f7faf9;
        border: 1px solid #e5eeec;
        border-radius: 8px;
        padding: 12px 14px;
    }

    .user-edit-page .info-box-title {
        color: #174a43;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .user-edit-page .info-box-text {
        color: #8898aa;
        font-size: 11px;
        margin-bottom: 0;
    }
</style>
@endpush

@section('content')
<div class="user-edit-page">
    <div class="row">
        <div class="col-lg-8 col-xl-7">
            <div class="card page-card">
                <div class="page-card-header">
                    <div class="page-title-small">
                        Edit User
                    </div>

                    <p class="page-description">
                        Perbarui informasi pengguna dengan benar.
                    </p>
                </div>

                <div class="border-top"></div>

                <div class="card-body p-4">
                    <form
                        method="POST"
                        action="{{ route('admin.users.update', $user->id) }}"
                    >
                        @csrf
                        @method('PUT')

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
                                value="{{ old('name', $user->name) }}"
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
                                value="{{ old('email', $user->email) }}"
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

                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                    Admin
                                </option>

                                <option value="petugas" {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>
                                    Petugas
                                </option>

                                <option value="koordinator" {{ old('role', $user->role) == 'koordinator' ? 'selected' : '' }}>
                                    Koordinator
                                </option>
                            </select>

                            @error('role')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="info-box mb-4">
                            <div class="info-box-title">
                                Ubah Password
                            </div>

                            <p class="info-box-text">
                                Kosongkan password jika tidak ingin mengubah password pengguna.
                            </p>

                            <div class="mt-3">
                                <label for="password" class="form-label">
                                    Password Baru
                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Masukkan password baru"
                                >

                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mt-3">
                                <label for="password_confirmation" class="form-label">
                                    Konfirmasi Password
                                </label>

                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control"
                                    placeholder="Ulangi password baru"
                                >
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a
                                href="{{ route('admin.users.index') }}"
                                class="btn btn-outline-secondary btn-sm"
                            >
                                <i class="fas fa-arrow-left me-1"></i>
                                Kembali
                            </a>

                            <button
                                type="submit"
                                class="btn btn-jade btn-sm"
                            >
                                <i class="fas fa-save me-1"></i>
                                Perbarui User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection