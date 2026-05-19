<?php

namespace App\Http\Controllers;

use App\Models\JenisPelanggaran;
use Illuminate\Http\Request;

class JenisPelanggaranController extends Controller
{
    public function index()
    {
        $jenisList = JenisPelanggaran::withCount('transaksiPelanggaran')
            ->orderBy('kategori')
            ->orderBy('nama_pelanggaran')
            ->get();

        return view('jenis-pelanggaran.index', compact('jenisList'));
    }

    public function create()
    {
        return view('jenis-pelanggaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggaran' => 'required|string|max:150|unique:jenis_pelanggaran,nama_pelanggaran',
            'deskripsi'        => 'nullable|string|max:500',
            'bobot_poin'       => 'required|integer|min:1|max:100',
            'kategori'         => 'required|in:ringan,sedang,berat',
            'status'           => 'required|in:aktif,nonaktif',
        ], [
            'nama_pelanggaran.required' => 'Nama pelanggaran wajib diisi.',
            'nama_pelanggaran.unique'   => 'Nama pelanggaran sudah ada.',
            'bobot_poin.required'       => 'Bobot poin wajib diisi.',
            'bobot_poin.min'            => 'Bobot poin minimal 1.',
            'bobot_poin.max'            => 'Bobot poin maksimal 100.',
            'kategori.required'         => 'Kategori wajib dipilih.',
            'status.required'           => 'Status wajib dipilih.',
        ]);

        JenisPelanggaran::create($validated);

        return redirect()->route('jenis-pelanggaran.index')
            ->with('success', 'Jenis pelanggaran berhasil ditambahkan.');
    }

    public function show(JenisPelanggaran $jenisPelanggaran)
    {
        return redirect()->route('jenis-pelanggaran.index');
    }

    public function edit(JenisPelanggaran $jenisPelanggaran)
    {
        return view('jenis-pelanggaran.edit', compact('jenisPelanggaran'));
    }

    public function update(Request $request, JenisPelanggaran $jenisPelanggaran)
    {
        $validated = $request->validate([
            'nama_pelanggaran' => 'required|string|max:150|unique:jenis_pelanggaran,nama_pelanggaran,' . $jenisPelanggaran->id_jenis . ',id_jenis',
            'deskripsi'        => 'nullable|string|max:500',
            'bobot_poin'       => 'required|integer|min:1|max:100',
            'kategori'         => 'required|in:ringan,sedang,berat',
            'status'           => 'required|in:aktif,nonaktif',
        ], [
            'nama_pelanggaran.required' => 'Nama pelanggaran wajib diisi.',
            'nama_pelanggaran.unique'   => 'Nama pelanggaran sudah digunakan oleh data lain.',
            'bobot_poin.required'       => 'Bobot poin wajib diisi.',
            'bobot_poin.min'            => 'Bobot poin minimal 1.',
            'bobot_poin.max'            => 'Bobot poin maksimal 100.',
            'kategori.required'         => 'Kategori wajib dipilih.',
            'status.required'           => 'Status wajib dipilih.',
        ]);

        $jenisPelanggaran->update($validated);

        return redirect()->route('jenis-pelanggaran.index')
            ->with('success', 'Jenis pelanggaran berhasil diperbarui.');
    }

    public function destroy(JenisPelanggaran $jenisPelanggaran)
    {
        if ($jenisPelanggaran->transaksiPelanggaran()->count() > 0) {
            return back()->with('error',
                'Jenis pelanggaran tidak dapat dihapus karena sudah digunakan pada ' .
                $jenisPelanggaran->transaksiPelanggaran()->count() . ' transaksi.'
            );
        }

        $jenisPelanggaran->delete();

        return redirect()->route('jenis-pelanggaran.index')
            ->with('success', 'Jenis pelanggaran berhasil dihapus.');
    }
}
