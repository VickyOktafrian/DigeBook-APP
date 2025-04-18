<?php

namespace App\Http\Controllers\Books;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Books;
use App\Models\Categories;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    // Fungsi untuk mengambil buku dari API dan memasukkannya ke database
    public function getBooks()
    {
        // Mengambil data buku dari API eksternal
        $response = Http::get('bukuacak-9bdcb4ef2605.herokuapp.com/api/v1/book/');

        // Cek apakah request berhasil
        if ($response->successful()) {
            $json = $response->json();
            $bookList = $json['books'];
            // dd($bookList);

            // Ambil semua kategori yang ada di database
            $categories = Categories::all();

            // Loop untuk memproses setiap buku yang diambil
            foreach ($bookList as $data) {
                // Pilih kategori secara acak dari daftar kategori yang ada
                $category = $categories->random(); // Memilih kategori acak

                // Menyimpan data buku ke database
                Books::firstOrCreate(
                    ['title' => $data['title']], // Cek jika buku sudah ada berdasarkan judul
                    [
                        'slug' => Str::slug($data['title']),
                        'description' => $data['summary'] ?? 'Tidak ada deskripsi.',
                        'author' => isset($data['author'][0]) ? $data['author'][0] : 'Tidak diketahui',
                        'publisher' => $data['publisher'] ?? 'Tidak diketahui',
                        'isbn' => $data['details']['isbn'] ?? null,
                        'price' => 30000, 
                        'cover_image' => $data['cover_image'] ?? null,
                        'category_id' => $category->id, 
                    ]
                );
            }

            return response()->json(['message' => 'Data buku berhasil dimasukkan.']);
        }

        // Jika request ke API gagal
        return response()->json(['message' => 'Gagal mengambil data dari API.'], $response->status());
    }
}
