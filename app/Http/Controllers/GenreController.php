<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function edit($id)
{
    // Temukan data genre berdasarkan ID
    $genre = Genre::findOrFail($id);

    // Render view untuk mengedit genre
    return view('Admins.data-genre.edit-genre', compact('genre'));
}


        public function updateGenre(Request $request, $id)
        {
            // Validasi data yang diterima dari request
            $request->validate([
                'nama_genre' => 'required|string|max:255',
            ]);

            // Temukan data genre berdasarkan ID
            $genre = Genre::findOrFail($id);

            // Perbarui nama genre dengan data baru dari request
            $genre->nama_genre = $request->nama_genre;

            // Simpan perubahan
            $genre->save();

            // Redirect ke halaman dengan pesan sukses
            return redirect()->route('genre')->with('success', 'Genre berhasil diperbarui.');
        }
}
