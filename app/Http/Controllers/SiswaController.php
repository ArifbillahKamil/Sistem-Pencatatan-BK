<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with(['kelas'])
            ->orderBy('id_kelas')
            ->orderBy('nama_siswa');

        // Filter by kelas
        if ($request->filled('kelas')) {
            $query->where('id_kelas', $request->kelas);
        }

        // Search by name / NISN
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $siswa      = $query->get();
        $kelasList  = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('siswa.index', compact('siswa', 'kelasList'));
    }

    public function create()
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('siswa.create', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kelas'      => 'required|exists:kelas,id_kelas',
            'nisn'          => 'required|string|size:10|unique:siswa,nisn',
            'nama_siswa'    => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date|before:today',
            'alamat'        => 'nullable|string|max:255',
            'no_telp'       => 'nullable|string|max:20',
        ], [
            'id_kelas.required'      => 'Kelas wajib dipilih.',
            'id_kelas.exists'        => 'Kelas yang dipilih tidak valid.',
            'nisn.required'          => 'NISN wajib diisi.',
            'nisn.size'              => 'NISN harus 10 digit.',
            'nisn.unique'            => 'NISN sudah terdaftar.',
            'nama_siswa.required'    => 'Nama siswa wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before'   => 'Tanggal lahir harus sebelum hari ini.',
        ]);

        Siswa::create($validated);

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa)
    {
        $siswa->load([
            'kelas',
            'transaksiPelanggaran' => fn ($q) => $q->with(['jenisPelanggaran', 'pelapor'])
                                                    ->latest('tanggal_kejadian'),
            'logPeringatan'        => fn ($q) => $q->latest('tanggal_terbit'),
        ]);

        return view('siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('siswa.edit', compact('siswa', 'kelasList'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'id_kelas'      => 'required|exists:kelas,id_kelas',
            'nisn'          => 'required|string|size:10|unique:siswa,nisn,' . $siswa->id_siswa . ',id_siswa',
            'nama_siswa'    => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date|before:today',
            'alamat'        => 'nullable|string|max:255',
            'no_telp'       => 'nullable|string|max:20',
        ], [
            'id_kelas.required'      => 'Kelas wajib dipilih.',
            'nisn.required'          => 'NISN wajib diisi.',
            'nisn.size'              => 'NISN harus 10 digit.',
            'nisn.unique'            => 'NISN sudah terdaftar pada siswa lain.',
            'nama_siswa.required'    => 'Nama siswa wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before'   => 'Tanggal lahir harus sebelum hari ini.',
        ]);

        $siswa->update($validated);

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        if ($siswa->transaksiPelanggaran()->count() > 0) {
            return back()->with('error', 'Siswa tidak dapat dihapus karena masih memiliki data pelanggaran.');
        }

        $siswa->logPeringatan()->delete();
        $siswa->delete();

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }
}
