<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatans = Kegiatan::latest()
            ->paginate(10);

        return view(
            'pages.kegiatan.index',
            compact('kegiatans')
        );
    }

    public function create()
    {
        return view('pages.kegiatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_kegiatan' => [
                'required',
                'string',
                'max:255',
                'unique:kegiatans,kode_kegiatan',
            ],
            'nama_kegiatan' => [
                'required',
                'string',
                'max:255',
            ],
            'deskripsi' => [
                'nullable',
                'string',
            ],
            'aktif' => [
                'nullable',
                'boolean',
            ],
        ], [
            'kode_kegiatan.required' => 'Kode kegiatan wajib diisi.',
            'kode_kegiatan.unique' => 'Kode kegiatan sudah digunakan.',
            'nama_kegiatan.required' => 'Nama kegiatan wajib diisi.',
        ]);

        $validated['aktif'] = $request->boolean('aktif');

        Kegiatan::create($validated);

        return redirect()
            ->route('kegiatan.index')
            ->with(
                'success',
                'Kegiatan berhasil ditambahkan.'
            );
    }

    public function show(Kegiatan $kegiatan)
    {
        $kegiatan->load([
            'jadwalDetails.posyandu',
            'jadwalDetails.jadwal',
        ]);

        return view(
            'pages.kegiatan.show',
            compact('kegiatan')
        );
    }

    public function edit(Kegiatan $kegiatan)
    {
        return view(
            'pages.kegiatan.edit',
            compact('kegiatan')
        );
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $validated = $request->validate([
            'kode_kegiatan' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kegiatans', 'kode_kegiatan')
                    ->ignore($kegiatan->id),
            ],
            'nama_kegiatan' => [
                'required',
                'string',
                'max:255',
            ],
            'deskripsi' => [
                'nullable',
                'string',
            ],
            'aktif' => [
                'nullable',
                'boolean',
            ],
        ], [
            'kode_kegiatan.required' => 'Kode kegiatan wajib diisi.',
            'kode_kegiatan.unique' => 'Kode kegiatan sudah digunakan.',
            'nama_kegiatan.required' => 'Nama kegiatan wajib diisi.',
        ]);

        $validated['aktif'] = $request->boolean('aktif');

        $kegiatan->update($validated);

        return redirect()
            ->route('kegiatan.index')
            ->with(
                'success',
                'Kegiatan berhasil diperbarui.'
            );
    }

    public function destroy(Kegiatan $kegiatan)
    {
        if ($kegiatan->jadwalDetails()->exists()) {
            return redirect()
                ->route('kegiatan.index')
                ->with(
                    'error',
                    'Kegiatan tidak dapat dihapus karena sudah digunakan dalam jadwal.'
                );
        }

        $kegiatan->delete();

        return redirect()
            ->route('kegiatan.index')
            ->with(
                'success',
                'Kegiatan berhasil dihapus.'
            );
    }
}