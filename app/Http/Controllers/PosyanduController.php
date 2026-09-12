<?php

namespace App\Http\Controllers;

use App\Models\Posyandu;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class PosyanduController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Posyandu::query()
            ->with('wilayah')
            ->withCount('hariOperasionals')
            ->orderBy('id');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($query) use ($search) {
                $query
                    ->where('kode_posyandu', 'like', "%{$search}%")
                    ->orWhere('nama_posyandu', 'like', "%{$search}%")
                    ->orWhere('ketua', 'like', "%{$search}%")
                    ->orWhere('kontak', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhereHas('wilayah', function ($wilayahQuery) use ($search) {
                        $wilayahQuery->where(
                            'nama_wilayah',
                            'like',
                            "%{$search}%"
                        );
                    });
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->where('aktif', true);
            }

            if ($request->status === 'nonaktif') {
                $query->where('aktif', false);
            }
        }

        $posyandus = $query
            ->paginate(10)
            ->withQueryString();

        return view(
            'pages.posyandu.index',
            compact('posyandus')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $wilayahs = Wilayah::query()
            ->orderBy('nama_wilayah')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Preview kode berikutnya
        |--------------------------------------------------------------------------
        */

        $kodeBerikutnya = $this->generateKodePosyandu();

        return view(
            'pages.posyandu.create',
            compact(
                'wilayahs',
                'kodeBerikutnya'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'wilayah_id' => [
                'required',
                'integer',
                'exists:wilayahs,id',
            ],

            'nama_posyandu' => [
                'required',
                'string',
                'max:255',
            ],

            'alamat' => [
                'nullable',
                'string',
            ],

            'ketua' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kontak' => [
                'nullable',
                'string',
                'max:30',
            ],

            'aktif' => [
                'nullable',
                'boolean',
            ],
        ], [
            'wilayah_id.required' =>
                'Wilayah wajib dipilih.',

            'wilayah_id.exists' =>
                'Wilayah yang dipilih tidak tersedia.',

            'nama_posyandu.required' =>
                'Nama Posyandu wajib diisi.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Generate kode otomatis dan berurutan
        |--------------------------------------------------------------------------
        */

        $validated['kode_posyandu'] =
            $this->generateKodePosyandu();

        $validated['aktif'] =
            $request->boolean('aktif');

        Posyandu::create($validated);

        return redirect()
            ->route('posyandu.index')
            ->with(
                'success',
                'Posyandu berhasil ditambahkan dengan kode '
                . $validated['kode_posyandu']
                . '.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Posyandu $posyandu)
    {
        $posyandu->load([
            'wilayah',
            'hariOperasionals' => function ($query) {
                $query
                    ->orderBy('hari')
                    ->orderBy('id');
            },
            'jadwalDetails.jadwal',
            'jadwalDetails.kegiatan',
        ]);

        return view(
            'pages.posyandu.show',
            compact('posyandu')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Posyandu $posyandu)
    {
        $wilayahs = Wilayah::query()
            ->orderBy('nama_wilayah')
            ->get();

        return view(
            'pages.posyandu.edit',
            compact(
                'posyandu',
                'wilayahs'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Posyandu $posyandu
    ) {
        $validated = $request->validate([
            'wilayah_id' => [
                'required',
                'integer',
                'exists:wilayahs,id',
            ],

            'nama_posyandu' => [
                'required',
                'string',
                'max:255',
            ],

            'alamat' => [
                'nullable',
                'string',
            ],

            'ketua' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kontak' => [
                'nullable',
                'string',
                'max:30',
            ],

            'aktif' => [
                'nullable',
                'boolean',
            ],
        ], [
            'wilayah_id.required' =>
                'Wilayah wajib dipilih.',

            'wilayah_id.exists' =>
                'Wilayah yang dipilih tidak tersedia.',

            'nama_posyandu.required' =>
                'Nama Posyandu wajib diisi.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Kode tidak diubah saat edit
        |--------------------------------------------------------------------------
        */

        $validated['aktif'] =
            $request->boolean('aktif');

        $posyandu->update($validated);

        return redirect()
            ->route('posyandu.index')
            ->with(
                'success',
                'Data Posyandu berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(Posyandu $posyandu)
    {
        if ($posyandu->jadwalDetails()->exists()) {
            return back()->with(
                'error',
                'Posyandu tidak dapat dihapus karena sudah digunakan pada jadwal.'
            );
        }

        if ($posyandu->hariOperasionals()->exists()) {
            return back()->with(
                'error',
                'Posyandu tidak dapat dihapus karena masih memiliki hari operasional.'
            );
        }

        $posyandu->delete();

        return redirect()
            ->route('posyandu.index')
            ->with(
                'success',
                'Posyandu berhasil dihapus.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE KODE POSYANDU OTOMATIS
    |--------------------------------------------------------------------------
    */

    private function generateKodePosyandu(): string
    {
        return DB::transaction(function () {
            $kodeTerakhir = Posyandu::query()
                ->where('kode_posyandu', 'like', 'POS-%')
                ->orderByDesc('id')
                ->value('kode_posyandu');

            if ($kodeTerakhir) {
                $nomorTerakhir = (int) str_replace(
                    'POS-',
                    '',
                    $kodeTerakhir
                );
            } else {
                $nomorTerakhir = 0;
            }

            $nomorBaru = $nomorTerakhir + 1;

            do {
                $kodeBaru = 'POS-' . str_pad(
                    $nomorBaru,
                    3,
                    '0',
                    STR_PAD_LEFT
                );

                $kodeSudahAda = Posyandu::query()
                    ->where('kode_posyandu', $kodeBaru)
                    ->exists();

                if ($kodeSudahAda) {
                    $nomorBaru++;
                }

            } while ($kodeSudahAda);

            return $kodeBaru;
        });
    }
}