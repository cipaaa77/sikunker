<?php

namespace App\Http\Controllers;

use App\Models\JadwalBulanan;
use App\Models\JadwalDetail;
use App\Models\Posyandu;
use App\Models\Kegiatan;
use App\Models\HariOperasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JadwalBulananController extends Controller
{
    /**
     * Menampilkan daftar jadwal bulanan.
     */
    public function index()
    {
        $jadwals = JadwalBulanan::with([
                'dibuatOleh',
                'approvedBy',
            ])
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->paginate(10);

        return view('pages.jadwal.index', compact('jadwals'));
    }

    /**
     * Halaman membuat jadwal.
     *
     * Pada tahap ini belum ada data yang disimpan ke database.
     * Data jadwal baru disimpan ketika tombol Simpan ditekan.
     */
    public function create()
    {
        $posyandus = Posyandu::with('wilayah')
            ->where('aktif', true)
            ->orderBy('nama_posyandu')
            ->get();

        $kegiatans = Kegiatan::where('aktif', true)
            ->orderBy('nama_kegiatan')
            ->get();

        $hariOperasionals = HariOperasional::with('posyandu')
            ->where('aktif', true)
            ->get();

        return view('pages.jadwal.create', compact(
            'posyandus',
            'kegiatans',
            'hariOperasionals'
        ));
    }

    /**
     * Menyimpan jadwal bulanan beserta detailnya.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bulan' => [
                'required',
                'integer',
                'between:1,12',
            ],

            'tahun' => [
                'required',
                'integer',
                'min:2020',
                'max:2100',
            ],

            'catatan' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'details' => [
                'required',
                'array',
                'min:1',
            ],

            'details.*.posyandu_id' => [
                'required',
                'exists:posyandus,id',
            ],

            'details.*.kegiatan_id' => [
                'required',
                'exists:kegiatans,id',
            ],

            'details.*.tgl_mulai' => [
                'required',
                'date',
            ],

            'details.*.tgl_selesai' => [
                'required',
                'date',
                'after_or_equal:details.*.tgl_mulai',
            ],

            'details.*.jam_mulai' => [
                'nullable',
                'date_format:H:i',
            ],

            'details.*.jam_selesai' => [
                'nullable',
                'date_format:H:i',
            ],

            'details.*.keterangan' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'details.required' => 'Minimal harus ada satu detail jadwal.',
            'details.min' => 'Minimal harus ada satu detail jadwal.',
            'details.*.posyandu_id.required' => 'Posyandu wajib dipilih.',
            'details.*.kegiatan_id.required' => 'Kegiatan wajib dipilih.',
            'details.*.tgl_mulai.required' => 'Tanggal mulai wajib diisi.',
            'details.*.tgl_selesai.required' => 'Tanggal selesai wajib diisi.',
            'details.*.tgl_selesai.after_or_equal' =>
                'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ]);

        $bulan = (int) $validated['bulan'];
        $tahun = (int) $validated['tahun'];

        /*
        |--------------------------------------------------------------------------
        | Validasi periode jadwal
        |--------------------------------------------------------------------------
        */

        $periodeSudahAda = JadwalBulanan::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->exists();

        if ($periodeSudahAda) {
            return back()
                ->withInput()
                ->with('error', 'Jadwal untuk bulan dan tahun tersebut sudah tersedia.');
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi tanggal harus sesuai dengan bulan dan tahun jadwal
        |--------------------------------------------------------------------------
        */

        foreach ($validated['details'] as $index => $detail) {
            $tanggalMulai = Carbon::parse($detail['tgl_mulai']);
            $tanggalSelesai = Carbon::parse($detail['tgl_selesai']);

            if (
                $tanggalMulai->month !== $bulan ||
                $tanggalMulai->year !== $tahun ||
                $tanggalSelesai->month !== $bulan ||
                $tanggalSelesai->year !== $tahun
            ) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Tanggal pada detail ke-' . ($index + 1) .
                        ' harus berada pada bulan dan tahun jadwal yang dipilih.'
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan header dan detail dalam satu transaksi
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($validated, $bulan, $tahun) {
            $jadwal = JadwalBulanan::create([
                'bulan' => $bulan,
                'tahun' => $tahun,
                'status' => 'draft',
                'catatan' => $validated['catatan'] ?? null,
                'dibuat_oleh' => auth()->id(),
            ]);

            foreach ($validated['details'] as $detail) {
                $jadwal->details()->create([
                    'posyandu_id' => $detail['posyandu_id'],
                    'kegiatan_id' => $detail['kegiatan_id'],
                    'tgl_mulai' => $detail['tgl_mulai'],
                    'tgl_selesai' => $detail['tgl_selesai'],
                    'jam_mulai' => $detail['jam_mulai'] ?? null,
                    'jam_selesai' => $detail['jam_selesai'] ?? null,
                    'keterangan' => $detail['keterangan'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('jadwal.index')
            ->with('success', 'Jadwal bulanan berhasil disimpan.');
    }

    /**
     * Menampilkan detail jadwal.
     */
    public function show(JadwalBulanan $jadwal)
    {
        $jadwal->load([
            'dibuatOleh',
            'approvedBy',
            'details.posyandu.wilayah',
            'details.kegiatan',
            'approvals.user',
            'statusLogs.user',
        ]);

        return view('pages.jadwal.show', compact('jadwal'));
    }

    /**
     * Halaman edit jadwal.
     */
    public function edit(JadwalBulanan $jadwal)
    {
        if (!in_array($jadwal->status, ['draft', 'ditolak'])) {
            return redirect()
                ->route('jadwal.index')
                ->with(
                    'error',
                    'Jadwal dengan status tersebut tidak dapat diubah.'
                );
        }

        $jadwal->load([
            'details.posyandu.wilayah',
            'details.kegiatan',
        ]);

        $posyandus = Posyandu::with('wilayah')
            ->where('aktif', true)
            ->orderBy('nama_posyandu')
            ->get();

        $kegiatans = Kegiatan::where('aktif', true)
            ->orderBy('nama_kegiatan')
            ->get();

        $hariOperasionals = HariOperasional::with('posyandu')
            ->where('aktif', true)
            ->get();

        return view('pages.jadwal.edit', compact(
            'jadwal',
            'posyandus',
            'kegiatans',
            'hariOperasionals'
        ));
    }

    /**
     * Memperbarui jadwal bulanan.
     */
    public function update(Request $request, JadwalBulanan $jadwal)
    {
        if (!in_array($jadwal->status, ['draft', 'ditolak'])) {
            return redirect()
                ->route('jadwal.index')
                ->with(
                    'error',
                    'Jadwal dengan status tersebut tidak dapat diubah.'
                );
        }

        $validated = $request->validate([
            'bulan' => [
                'required',
                'integer',
                'between:1,12',
            ],

            'tahun' => [
                'required',
                'integer',
                'min:2020',
                'max:2100',
            ],

            'catatan' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'details' => [
                'required',
                'array',
                'min:1',
            ],

            'details.*.id' => [
                'nullable',
                'integer',
                'exists:jadwal_details,id',
            ],

            'details.*.posyandu_id' => [
                'required',
                'exists:posyandus,id',
            ],

            'details.*.kegiatan_id' => [
                'required',
                'exists:kegiatans,id',
            ],

            'details.*.tgl_mulai' => [
                'required',
                'date',
            ],

            'details.*.tgl_selesai' => [
                'required',
                'date',
                'after_or_equal:details.*.tgl_mulai',
            ],

            'details.*.jam_mulai' => [
                'nullable',
                'date_format:H:i',
            ],

            'details.*.jam_selesai' => [
                'nullable',
                'date_format:H:i',
            ],

            'details.*.keterangan' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $bulan = (int) $validated['bulan'];
        $tahun = (int) $validated['tahun'];

        $periodeSudahAda = JadwalBulanan::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('id', '!=', $jadwal->id)
            ->exists();

        if ($periodeSudahAda) {
            return back()
                ->withInput()
                ->with('error', 'Jadwal untuk bulan dan tahun tersebut sudah tersedia.');
        }

        foreach ($validated['details'] as $index => $detail) {
            $tanggalMulai = Carbon::parse($detail['tgl_mulai']);
            $tanggalSelesai = Carbon::parse($detail['tgl_selesai']);

            if (
                $tanggalMulai->month !== $bulan ||
                $tanggalMulai->year !== $tahun ||
                $tanggalSelesai->month !== $bulan ||
                $tanggalSelesai->year !== $tahun
            ) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Tanggal pada detail ke-' . ($index + 1) .
                        ' harus sesuai dengan bulan dan tahun jadwal.'
                    );
            }
        }

        DB::transaction(function () use ($validated, $jadwal, $bulan, $tahun) {
            $jadwal->update([
                'bulan' => $bulan,
                'tahun' => $tahun,
                'status' => 'draft',
                'catatan' => $validated['catatan'] ?? null,
                'approved_by' => null,
                'approved_at' => null,
            ]);

            $detailIds = [];

            foreach ($validated['details'] as $detail) {
                $detailData = [
                    'posyandu_id' => $detail['posyandu_id'],
                    'kegiatan_id' => $detail['kegiatan_id'],
                    'tgl_mulai' => $detail['tgl_mulai'],
                    'tgl_selesai' => $detail['tgl_selesai'],
                    'jam_mulai' => $detail['jam_mulai'] ?? null,
                    'jam_selesai' => $detail['jam_selesai'] ?? null,
                    'keterangan' => $detail['keterangan'] ?? null,
                ];

                if (!empty($detail['id'])) {
                    $detailModel = $jadwal->details()
                        ->where('id', $detail['id'])
                        ->firstOrFail();

                    $detailModel->update($detailData);
                    $detailIds[] = $detailModel->id;
                } else {
                    $detailModel = $jadwal->details()
                        ->create($detailData);

                    $detailIds[] = $detailModel->id;
                }
            }

            $jadwal->details()
                ->whereNotIn('id', $detailIds)
                ->delete();
        });

        return redirect()
            ->route('jadwal.index')
            ->with('success', 'Jadwal bulanan berhasil diperbarui.');
    }

    /**
     * Menghapus jadwal bulanan.
     */
    public function destroy(JadwalBulanan $jadwal)
    {
        if (!in_array($jadwal->status, ['draft', 'ditolak'])) {
            return redirect()
                ->route('jadwal.index')
                ->with(
                    'error',
                    'Jadwal dengan status tersebut tidak dapat dihapus.'
                );
        }

        $namaBulan = Carbon::create()
            ->month($jadwal->bulan)
            ->locale('id')
            ->translatedFormat('F');

        $jadwal->delete();

        return redirect()
            ->route('jadwal.index')
            ->with(
                'success',
                "Data jadwal bulan {$namaBulan} {$jadwal->tahun} beserta seluruh detail kegiatannya berhasil dihapus."
            );
    }

    /**
     * Mengajukan jadwal untuk proses persetujuan.
     */
    public function submit(JadwalBulanan $jadwal)
    {
        if ($jadwal->status !== 'draft') {
            return back()
                ->with('error', 'Hanya jadwal draft yang dapat diajukan.');
        }

        $jadwal->update([
            'status' => 'diajukan',
        ]);

        return back()
            ->with('success', 'Jadwal berhasil diajukan untuk persetujuan.');
    }

    /**
     * Menyetujui atau menolak jadwal.
     */
    public function approve(Request $request, JadwalBulanan $jadwal)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:disetujui,ditolak',
            ],

            'catatan' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        if ($jadwal->status !== 'diajukan') {
            return back()
                ->with('error', 'Jadwal belum diajukan untuk persetujuan.');
        }

        $jadwal->update([
            'status' => $validated['status'],
            'catatan' => $validated['catatan'] ?? $jadwal->catatan,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $message = $validated['status'] === 'disetujui'
            ? 'Jadwal berhasil disetujui.'
            : 'Jadwal ditolak dan dapat direvisi.';

        return redirect()
            ->route('jadwal.index')
            ->with('success', $message);
    }

    /**
     * Laporan jadwal berdasarkan bulan dan tahun.
     */
    public function laporan(Request $request)
    {
        $bulan = (int) ($request->bulan ?? now()->month);
        $tahun = (int) ($request->tahun ?? now()->year);

        $jadwal = JadwalBulanan::with([
                'details.posyandu.wilayah',
                'details.kegiatan',
            ])
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->whereIn('status', ['disetujui', 'final'])
            ->first();

        return view('pages.jadwal.laporan', compact(
            'jadwal',
            'bulan',
            'tahun'
        ));
    }
}