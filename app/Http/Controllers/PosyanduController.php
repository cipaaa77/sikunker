<?php

namespace App\Http\Controllers;

use App\Models\Posyandu;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PosyanduController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $posyandus = Posyandu::with('wilayah')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('kode_posyandu', 'like', "%{$search}%")
                        ->orWhere('nama_posyandu', 'like', "%{$search}%")
                        ->orWhere('ketua', 'like', "%{$search}%")
                        ->orWhereHas('wilayah', function ($query) use ($search) {
                            $query->where('nama_wilayah', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'pages.posyandu.index',
            compact('posyandus', 'search')
        );
    }

    public function create()
    {
        $wilayahs = Wilayah::where('aktif', true)
            ->orderBy('nama_wilayah')
            ->get();

        return view(
            'pages.posyandu.create',
            compact('wilayahs')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'wilayah_id' => [
                'required',
                'exists:wilayahs,id',
            ],
            'kode_posyandu' => [
                'required',
                'string',
                'max:255',
                'unique:posyandus,kode_posyandu',
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
                'max:255',
            ],
            'aktif' => [
                'nullable',
                'boolean',
            ],
        ], [
            'wilayah_id.required' => 'Wilayah wajib dipilih.',
            'wilayah_id.exists' => 'Wilayah tidak ditemukan.',
            'kode_posyandu.required' => 'Kode Posyandu wajib diisi.',
            'kode_posyandu.unique' => 'Kode Posyandu sudah digunakan.',
            'nama_posyandu.required' => 'Nama Posyandu wajib diisi.',
        ]);

        $validated['aktif'] = $request->boolean('aktif');

        Posyandu::create($validated);

        return redirect()
            ->route('posyandu.index')
            ->with(
                'success',
                'Posyandu berhasil ditambahkan.'
            );
    }

    public function show(Posyandu $posyandu)
    {
        $posyandu->load([
            'wilayah',
            'hariOperasionals',
            'jadwalDetails.kegiatan',
        ]);

        return view(
            'pages.posyandu.show',
            compact('posyandu')
        );
    }

    public function edit(Posyandu $posyandu)
    {
        $wilayahs = Wilayah::where('aktif', true)
            ->orWhere('id', $posyandu->wilayah_id)
            ->orderBy('nama_wilayah')
            ->get();

        return view(
            'pages.posyandu.edit',
            compact('posyandu', 'wilayahs')
        );
    }

    public function update(Request $request, Posyandu $posyandu)
    {
        $validated = $request->validate([
            'wilayah_id' => [
                'required',
                'exists:wilayahs,id',
            ],
            'kode_posyandu' => [
                'required',
                'string',
                'max:255',
                Rule::unique('posyandus', 'kode_posyandu')
                    ->ignore($posyandu->id),
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
                'max:255',
            ],
            'aktif' => [
                'nullable',
                'boolean',
            ],
        ], [
            'wilayah_id.required' => 'Wilayah wajib dipilih.',
            'wilayah_id.exists' => 'Wilayah tidak ditemukan.',
            'kode_posyandu.required' => 'Kode Posyandu wajib diisi.',
            'kode_posyandu.unique' => 'Kode Posyandu sudah digunakan.',
            'nama_posyandu.required' => 'Nama Posyandu wajib diisi.',
        ]);

        $validated['aktif'] = $request->boolean('aktif');

        $posyandu->update($validated);

        return redirect()
            ->route('posyandu.index')
            ->with(
                'success',
                'Posyandu berhasil diperbarui.'
            );
    }

    public function destroy(Posyandu $posyandu)
    {
        if ($posyandu->hariOperasionals()->exists()) {
            return redirect()
                ->route('posyandu.index')
                ->with(
                    'error',
                    'Posyandu tidak dapat dihapus karena masih memiliki hari operasional.'
                );
        }

        if ($posyandu->jadwalDetails()->exists()) {
            return redirect()
                ->route('posyandu.index')
                ->with(
                    'error',
                    'Posyandu tidak dapat dihapus karena sudah digunakan dalam jadwal.'
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
}