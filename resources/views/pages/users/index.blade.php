@extends('layouts.app')

@section('title', 'Master User')

@push('styles')
<style>
    /* =========================================================
       USER CARD
    ========================================================= */

    .user-card {
        border: 1px solid #edf0f0;
        border-radius: 12px;
        overflow: hidden;
        background: #ffffff;
    }

    .user-header {
        padding: 18px 20px;
    }

    .user-title {
        margin-bottom: 4px;
        color: #263238;
        font-size: 15px;
        font-weight: 700;
    }

    .user-description {
        margin-bottom: 0;
        color: #6b7280;
        font-size: 12px;
    }

    /* =========================================================
       BUTTON JADE
    ========================================================= */

    .btn-jade {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 13px;
        border: 1px solid #174a43;
        border-radius: 7px;
        background: #174a43;
        color: #ffffff;
        font-size: 12px;
        transition:
            background-color .2s ease,
            border-color .2s ease,
            transform .2s ease;
    }

    .btn-jade:hover {
        border-color: #123d37;
        background: #123d37;
        color: #ffffff;
        transform: translateY(-1px);
    }

    .btn-jade:active {
        transform: scale(.98);
    }

    /* =========================================================
       SEARCH
    ========================================================= */

    .search-box {
        position: relative;
        width: 100%;
        max-width: 280px;
    }

    .search-box input {
        width: 100%;
        height: 35px;
        padding: 0 12px 0 36px;
        border: 1px solid #e1e7e4;
        border-radius: 7px;
        color: #263238;
        font-size: 12px;
        box-shadow: none;
    }

    .search-box input::placeholder {
        color: #9ca3af;
    }

    .search-box input:focus {
        border-color: #8dbdb4;
        box-shadow: 0 0 0 3px rgba(23, 74, 67, .08);
    }

    .search-icon {
        position: absolute;
        top: 50%;
        left: 13px;
        z-index: 2;
        color: #9aa5a3;
        font-size: 12px;
        pointer-events: none;
        transform: translateY(-50%);
    }

    /* =========================================================
       TABLE
    ========================================================= */

    .user-table {
        margin-bottom: 0;
        border-collapse: collapse;
    }

    .user-table thead th {
        padding: 13px 14px;
        border-bottom: 1px solid #edf0f0;
        background: #fafcfb;
        color: #374151;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
        vertical-align: middle;
    }

    .user-table tbody td {
        padding: 13px 14px;
        border-bottom: 1px solid #f0f2f1;
        color: #263238;
        font-size: 12px;
        white-space: nowrap;
        vertical-align: middle;
    }

    .user-table tbody tr {
        transition: background-color .2s ease;
    }

    .user-table tbody tr:hover {
        background: #f8fbfa;
    }

    .user-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* =========================================================
       USER DATA
    ========================================================= */

 .user-table-name {
    color: #263238 !important;
    font-size: 12px;
    font-weight: 700;
}
    .user-email {
        margin-top: 3px;
        color: #6b7280 !important;
        font-size: 11px;
    }

    .created-date {
        color: #374151 !important;
        font-size: 11px;
    }

    /* =========================================================
       ROLE
    ========================================================= */

    .role-wrapper {
        display: inline-flex;
        align-items: center;
        gap: 9px;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 9px;
        border-radius: 6px;
        background: #e8f2f0;
        color: #263238 !important;
        font-size: 11px;
        font-weight: 700;
    }

    .role-badge-user {
        background: #f1f3f5;
        color: #263238 !important;
    }

    /*
    | Status admin hanya bulatan hijau.
    | Tidak ada tulisan Aktif.
    */

    .admin-dot {
        display: inline-block;
        width: 9px;
        height: 9px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        background: #20c997;
        animation: softBlink 1.8s ease-in-out infinite;
    }

    /* =========================================================
       ACTION BUTTON
    ========================================================= */

    .action-btn {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 7px;
        font-size: 11px;
        margin: 0 2px;
        transition:
            background-color .2s ease,
            border-color .2s ease,
            color .2s ease,
            transform .2s ease;
    }

    .action-btn:hover {
        transform: translateY(-1px);
    }

    .action-btn:active {
        transform: scale(.94);
    }

    .btn-edit {
        border: 1px solid #c8dfd9;
        background: #ffffff;
        color: #21665c;
    }

    .btn-edit:hover {
        border-color: #a9cfc5;
        background: #e8f2f0;
        color: #174a43;
    }

    .btn-delete {
        border: 1px solid #f1c4c9;
        background: #ffffff;
        color: #dc3545;
    }

    .btn-delete:hover {
        border-color: #e7aab1;
        background: #fff0f1;
        color: #b02a37;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .empty-state {
        padding: 42px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 54px;
        height: 54px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
        border-radius: 14px;
        background: #e8f2f0;
        color: #174a43;
        font-size: 20px;
    }

    .empty-title {
        margin-bottom: 6px;
        color: #263238;
        font-size: 14px;
        font-weight: 700;
    }

    .empty-description {
        margin-bottom: 18px;
        color: #6b7280;
        font-size: 12px;
    }

    /* =========================================================
       PAGINATION
    ========================================================= */

    .pagination {
        margin-bottom: 0;
    }

    .pagination .page-link {
        border-color: #e5e9e7;
        color: #174a43;
        font-size: 12px;
    }

    .pagination .page-item.active .page-link {
        border-color: #174a43;
        background: #174a43;
        color: #ffffff;
    }

    .pagination .page-link:hover {
        background: #e8f2f0;
        color: #174a43;
    }

    /* =========================================================
       ANIMATION STATUS
    ========================================================= */

    @keyframes softBlink {
        0%,
        100% {
            opacity: 1;
            transform: scale(1);
            box-shadow: 0 0 0 3px rgba(32, 201, 151, .12);
        }

        50% {
            opacity: .4;
            transform: scale(.82);
            box-shadow: 0 0 0 5px rgba(32, 201, 151, .04);
        }
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 767px) {
        .user-header {
            padding: 15px;
        }

        .header-action {
            width: 100%;
            margin-top: 12px;
        }

        .header-action .btn {
            width: 100%;
        }

        .search-box {
            max-width: 100%;
        }

        .user-table {
            min-width: 650px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation: none !important;
            transition: none !important;
        }
    }
</style>
@endpush


@section('content')

<div class="card user-card">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="user-header">

        <div class="row align-items-center">

            <div class="col-md-7">

                <div class="user-title">
                    Data Master User
                </div>

                <p class="user-description">
                    Kelola akun pengguna dan hak akses sistem.
                </p>

            </div>

            <div class="col-md-5">

                <div class="d-flex justify-content-md-end header-action">

                    <a
                        href="{{ route('admin.users.create') }}"
                        class="btn btn-jade"
                    >
                        <i class="fas fa-plus"></i>
                        <span>Tambah User</span>
                    </a>

                </div>

            </div>

        </div>

    </div>


    <div class="border-top"></div>


    {{-- =====================================================
         SEARCH

         Tidak ada alert tambahan karena notifikasi sudah
         ditangani oleh layouts.app.
    ====================================================== --}}

    <div class="p-3">

        <div class="row align-items-center">

            <div class="col-md-8">

                <div class="search-box">

                    <i class="fas fa-search search-icon"></i>

                    <input
                        type="text"
                        id="searchUser"
                        class="form-control"
                        placeholder="Cari nama, email, atau role..."
                        autocomplete="off"
                    >

                </div>

            </div>

            <div class="col-md-4 text-md-end mt-2 mt-md-0">

                <small class="text-muted">

                    Menampilkan

                    <strong id="userTotal">

                        {{ method_exists($users, 'total') ? $users->total() : $users->count() }}

                    </strong>

                    user

                </small>

            </div>

        </div>

    </div>


    {{-- =====================================================
         TABLE
    ====================================================== --}}

    <div class="table-responsive">

        <table
            class="table user-table"
            id="userTable"
        >

            <thead>

                <tr>

                    <th class="ps-4">
                        No
                    </th>

                    <th>
                        Pengguna
                    </th>

                    <th>
                        Role
                    </th>

                    <th>
                        Dibuat
                    </th>

                    <th class="text-center">
                        Aksi
                    </th>

                </tr>

            </thead>


   <tbody>

    @forelse($users as $index => $user)

        <tr>

            {{-- Nomor --}}
            <td class="ps-4">

                @if(method_exists($users, 'firstItem'))

                    {{ $users->firstItem() + $index }}

                @else

                    {{ $index + 1 }}

                @endif

            </td>


            {{-- Data pengguna tanpa avatar atau inisial --}}
            <td>

                <div class="user-table-name">

                    {{ $user->name }}

                </div>

                <div class="user-email">

                    {{ $user->email }}

                </div>

            </td>


            {{-- Role --}}
            <td>

                <div class="role-wrapper">

                    @if($user->role === 'admin')

                        <span class="role-badge">

                            <i class="fas fa-shield-alt"></i>

                            {{ ucfirst($user->role) }}

                        </span>

                        {{-- Hanya bulatan hijau --}}
                        <span
                            class="admin-dot"
                            title="User aktif"
                            aria-label="User aktif"
                        ></span>

                    @else

                        <span class="role-badge role-badge-user">

                            <i class="fas fa-user"></i>

                            {{ ucfirst($user->role) }}

                        </span>

                    @endif

                </div>

            </td>


            {{-- Waktu dibuat dalam WIB --}}
            <td>

                <span
                    class="created-date"
                    title="Waktu Indonesia Barat"
                >

                    <i class="far fa-calendar-alt me-1"></i>

                    @if($user->created_at)

                        {{ $user->created_at
                            ->timezone('Asia/Jakarta')
                            ->format('d M Y, H:i') }}

                        WIB

                    @else

                        -

                    @endif

                </span>

            </td>


            {{-- Aksi --}}
            <td class="text-center">

                <a
                    href="{{ route('admin.users.edit', $user->id) }}"
                    class="btn action-btn btn-edit"
                    title="Edit user"
                    aria-label="Edit user {{ $user->name }}"
                >
                    <i class="fas fa-pen"></i>
                </a>


                <form
                    action="{{ route('admin.users.destroy', $user->id) }}"
                    method="POST"
                    class="d-inline delete-form"
                    data-name="{{ $user->name }}"
                >

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn action-btn btn-delete"
                        title="Hapus user"
                        aria-label="Hapus user {{ $user->name }}"
                    >
                        <i class="fas fa-trash"></i>
                    </button>

                </form>

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="5">

                <div class="empty-state">

                    <div class="empty-icon">

                        <i class="fas fa-users"></i>

                    </div>

                    <div class="empty-title">

                        Belum Ada Data User

                    </div>

                    <p class="empty-description">

                        Silakan tambahkan akun pengguna baru.

                    </p>

                    <a
                        href="{{ route('admin.users.create') }}"
                        class="btn btn-jade"
                    >
                        <i class="fas fa-plus"></i>
                        <span>Tambah User</span>
                    </a>

                </div>

            </td>

        </tr>

    @endforelse

</tbody>
        </table>

    </div>


    {{-- =====================================================
         PAGINATION
    ====================================================== --}}

    @if(method_exists($users, 'hasPages') && $users->hasPages())

        <div class="px-3 py-3 border-top">

            {{ $users->links() }}

        </div>

    @endif

</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | SEARCH USER
    |--------------------------------------------------------------------------
    */

    const searchInput = document.getElementById('searchUser');
    const userTable = document.getElementById('userTable');
    const userTotal = document.getElementById('userTotal');

    if (searchInput && userTable) {

        searchInput.addEventListener('input', function () {

            const keyword = this.value.toLowerCase().trim();
            const rows = userTable.querySelectorAll('tbody tr');

            let visibleRows = 0;

            rows.forEach(function (row) {

                const emptyState =
                    row.querySelector('.empty-state');

                if (emptyState) {
                    return;
                }

                const rowText =
                    row.textContent.toLowerCase();

                const matched =
                    rowText.includes(keyword);

                row.style.display =
                    matched ? '' : 'none';

                if (matched) {
                    visibleRows++;
                }

            });

            if (userTotal) {
                userTotal.textContent = visibleRows;
            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI HAPUS USER
    |--------------------------------------------------------------------------
    */

    const deleteForms =
        document.querySelectorAll('.delete-form');

    deleteForms.forEach(function (form) {

        form.addEventListener('submit', function (event) {

            const userName =
                form.getAttribute('data-name') || 'user ini';

            const confirmation = confirm(
                'Yakin ingin menghapus user "' +
                userName +
                '"?'
            );

            if (!confirmation) {
                event.preventDefault();
            }

        });

    });

});
</script>
@endpush