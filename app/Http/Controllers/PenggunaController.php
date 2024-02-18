<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function editUser($id)
    {
    // Logika untuk mengambil data pengguna berdasarkan ID
    $pengguna = User::findOrFail($id);

    // Kirim data pengguna ke view
    return view('Admins.data-pengguna.edit-pengguna', compact('pengguna'));
    }
    
    public function update(Request $request, $id)
    {
        // Validasi data yang diterima dari formulir
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,kasir', // Sesuaikan dengan role yang diperbolehkan
            // Tambahkan validasi untuk bidang lainnya jika diperlukan
        ]);

        // Cari pengguna berdasarkan ID
        $user = User::findOrFail($id);

        // Perbarui data pengguna
        $user->username = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->role = $validatedData['role'];
        // Perbarui bidang lainnya jika ada

        // Simpan perubahan
        $user->save();

        // Redirect ke halaman yang sesuai atau tampilkan pesan berhasil
        return redirect()->route('pengguna')->with('success', 'User data updated successfully');
    }
}
