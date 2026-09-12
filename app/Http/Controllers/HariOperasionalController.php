<?php

namespace App\Http\Controllers;

use App\Models\HariOperasional;
use App\Models\Posyandu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HariOperasionalController extends Controller
{
    public function index()
    {
        $hariOperasionals = HariOperasional::with('posyandu')
            ->orderBy('posyandu_id')
            ->orderBy('hari')
            ->paginate(10);

        return view(
            'pages.hari-operasional.index',
            compact('hariOperasionals')
        );
    }

    public function create()
    {
        $posyandus = Posyandu::where('aktif', true)
            ->orderBy('nama_posyandu')
            ->get();

        return view(
            'pages.hari-operasional.create',
            compact('posyandus')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'posyandu_id' => [
                'required',
                'exists:posyandus,id',
            ],
            'hari' => [
                'required',
                'integer',
                'between:1,7',
                Rule::unique('hari_operasionals', 'hari')
                    ->where(function ($query) use ($request) {
                        return $query->where(
                            'posyandu_id',
                            $request->posyandu_id
                        );
                    }),
            ],
            'jam_mulai' => [
                'required',
                'date_format:H:i',
            ],
            'jam_selesai' => [
                'required',
                'date_format:H:i',
                'after:jam_mulai',
            ],
            'aktif' => [
                'nullable',
                'boolean',
            ],
        ], [
            'posyandu_id.required' => 'Posyandu wajib dipilih.',
            'posyandu_id.exists' => 'Posyandu tidak ditemukan.',
            'hari.required' => 'Hari wajib dipilih.',
            'hari.between' => 'Hari tidak valid.',
            'hari.unique' => 'Hari tersebut sudah memiliki jadwal operasional untuk Posyandu ini.',
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',
        ]);

        $validated['aktif'] = $request->boolean('aktif');

        HariOperasional::create($validated);

        return redirect()
            ->route('hari-operasional.index')
            ->with(
                'success',
                'Hari operasional berhasil ditambahkan.'
            );
    }

    public function show(HariOperasional $hariOperasional)
    {
        $hariOperasional->load('posyandu');

        return view(
            'pages.hari-operasional.show',
            compact('hariOperasional')
        );
    }

    public function edit(HariOperasional $hariOperasional)
    {
        $posyandus = Posyandu::where('aktif', true)
            ->orWhere('id', $hariOperasional->posyandu_id)
            ->orderBy('nama_posyandu')
            ->get();

        return view(
            'pages.hari-operasional.edit',
            compact(
                'hariOperasional',
                'posyandus'
            )
        );
    }

    public function update(
        Request $request,
        HariOperasional $hariOperasional
    ) {
        $validated = $request->validate([
            'posyandu_id' => [
                'required',
                'exists:posyandus,id',
            ],
            'hari' => [
                'required',
                'integer',
                'between:1,7',
                Rule::unique('hari_operasionals', 'hari')
                    ->where(function ($query) use ($request) {
                        return $query->where(
                            'posyandu_id',
                            $request->posyandu_id
                        );
                    })
                    ->ignore($hariOperasional->id),
            ],
            'jam_mulai' => [
                'required',
                'date_format:H:i',
            ],
            'jam_selesai' => [
                'required',
                'date_format:H:i',
                'after:jam_mulai',
            ],
            'aktif' => [
                'nullable',
                'boolean',
            ],
        ], [
            'posyandu_id.required' => 'Posyandu wajib dipilih.',
            'posyandu_id.exists' => 'Posyandu tidak ditemukan.',
            'hari.required' => 'Hari wajib dipilih.',
            'hari.between' => 'Hari tidak valid.',
            'hari.unique' => 'Hari tersebut sudah memiliki jadwal operasional untuk Posyandu ini.',
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',
        ]);

        $validated['aktif'] = $request->boolean('aktif');

        $hariOperasional->update($validated);

        return redirect()
            ->route('hari-operasional.index')
            ->with(
                'success',
                'Hari operasional berhasil diperbarui.'
            );
    }

    public function destroy(HariOperasional $hariOperasional)
    {
        $hariOperasional->delete();

        return redirect()
            ->route('hari-operasional.index')
            ->with(
                'success',
                'Hari operasional berhasil dihapus.'
            );
    }
}