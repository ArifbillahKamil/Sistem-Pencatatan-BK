<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with('waliKelas')->orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        $waliKelasUsers = User::where('role', 'wali_kelas')->orderBy('nama_lengkap')->get();
        return view('kelas.create', compact('waliKelasUsers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_user'    => 'required|exists:users,id',
            'nama_kelas' => 'required|string|max:20',
            'tingkat'    => 'required|integer|in:7,8,9',
        ], [
            'id_user.required'    => 'Wali kelas wajib dipilih.',
            'id_user.exists'      => 'User yang dipilih tidak valid.',
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
            'tingkat.required'    => 'Tingkat wajib dipilih.',
            'tingkat.in'          => 'Tingkat harus 7, 8, atau 9.',
        ]);

        // Pastikan satu wali kelas hanya memegang satu kelas
        if (Kelas::where('id_user', $validated['id_user'])->exists()) {
            return back()->withInput()
                ->withErrors(['id_user' => 'Wali kelas ini sudah memegang kelas lain.']);
        }

        Kelas::create($validated);

        return redirect()->route('kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function show(Kelas $kela)
    {
        return redirect()->route('kelas.index');
    }

    public function edit(Kelas $kela)
    {
        $waliKelasUsers = User::where('role', 'wali_kelas')->orderBy('nama_lengkap')->get();
        return view('kelas.edit', compact('kela', 'waliKelasUsers'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $validated = $request->validate([
            'id_user'    => 'required|exists:users,id',
            'nama_kelas' => 'required|string|max:20',
            'tingkat'    => 'required|integer|in:7,8,9',
        ], [
            'id_user.required'    => 'Wali kelas wajib dipilih.',
            'id_user.exists'      => 'User yang dipilih tidak valid.',
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
            'tingkat.required'    => 'Tingkat wajib dipilih.',
            'tingkat.in'          => 'Tingkat harus 7, 8, atau 9.',
        ]);

        // Cek duplikat wali kelas (kecuali kelas ini sendiri)
        if (Kelas::where('id_user', $validated['id_user'])
                  ->where('id_kelas', '!=', $kela->id_kelas)
                  ->exists()) {
            return back()->withInput()
                ->withErrors(['id_user' => 'Wali kelas ini sudah memegang kelas lain.']);
        }

        $kela->update($validated);

        return redirect()->route('kelas.index')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        if ($kela->siswa()->count() > 0) {
            return back()->with('error', 'Kelas tidak dapat dihapus karena masih memiliki data siswa.');
        }

        $kela->delete();

        return redirect()->route('kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}
