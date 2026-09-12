<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    /**
     * Menampilkan daftar seluruh wilayah.
     */
    public function index()
    {
        $wilayahs = Wilayah::latest()->paginate(10);

        return view(
            'pages.wilayah.index',
            compact('wilayahs')
        );
    }

    /**
     * Menampilkan form tambah wilayah.
     */
    public function create()
    {
        return view('pages.wilayah.create');
    }

    /**
     * Menyimpan data wilayah baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_wilayah' => [
                'required',
                'string',
                'max:255',
            ],

            'rw' => [
                'nullable',
                'string',
                'max:50',
            ],

            'kelurahan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kecamatan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'alamat' => [
                'nullable',
                'string',
            ],

            'aktif' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Checkbox aktif
        |--------------------------------------------------------------------------
        */

        $validated['aktif'] = $request->boolean('aktif');

        /*
        |--------------------------------------------------------------------------
        | Simpan
        |--------------------------------------------------------------------------
        */

        Wilayah::create($validated);

        /*
        |--------------------------------------------------------------------------
        | Kembali ke daftar wilayah
        |--------------------------------------------------------------------------
        |
        | Nama route adalah wilayah.index
        | BUKAN pages.wilayah.index
        |
        */

        return redirect()
            ->route('wilayah.index')
            ->with(
                'success',
                'Wilayah berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan detail wilayah.
     */
    public function show(Wilayah $wilayah)
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil Posyandu yang berada di wilayah tersebut
        |--------------------------------------------------------------------------
        */

        $wilayah->load('posyandus');

        return view(
            'pages.wilayah.show',
            compact('wilayah')
        );
    }

    /**
     * Menampilkan form edit wilayah.
     */
    public function edit(Wilayah $wilayah)
    {
        return view(
            'pages.wilayah.edit',
            compact('wilayah')
        );
    }

    /**
     * Memperbarui data wilayah.
     */
    public function update(
        Request $request,
        Wilayah $wilayah
    ) {
        $validated = $request->validate([
            'nama_wilayah' => [
                'required',
                'string',
                'max:255',
            ],

            'rw' => [
                'nullable',
                'string',
                'max:50',
            ],

            'kelurahan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kecamatan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'alamat' => [
                'nullable',
                'string',
            ],

            'aktif' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Checkbox aktif
        |--------------------------------------------------------------------------
        */

        $validated['aktif'] = $request->boolean('aktif');

        /*
        |--------------------------------------------------------------------------
        | Update database
        |--------------------------------------------------------------------------
        */

        $wilayah->update($validated);

        /*
        |--------------------------------------------------------------------------
        | Kembali ke halaman index
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('wilayah.index')
            ->with(
                'success',
                'Wilayah berhasil diperbarui.'
            );
    }

    /**
     * Menghapus wilayah.
     */
    public function destroy(Wilayah $wilayah)
    {
        /*
        |--------------------------------------------------------------------------
        | Cek apakah wilayah masih digunakan oleh Posyandu
        |--------------------------------------------------------------------------
        */

        if ($wilayah->posyandus()->exists()) {

            return redirect()
                ->route('wilayah.index')
                ->with(
                    'error',
                    'Wilayah tidak dapat dihapus karena masih memiliki Posyandu.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus
        |--------------------------------------------------------------------------
        */

        $wilayah->delete();

        /*
        |--------------------------------------------------------------------------
        | Kembali ke daftar
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('wilayah.index')
            ->with(
                'success',
                'Wilayah berhasil dihapus.'
            );
    }
}