<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Models\Books;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BooksController extends Controller
{
    public function getBooks()
    {
        $response = Http::get('https://bukuacak-9bdcb4ef2605.herokuapp.com/api/v1/book');

        if ($response->successful()) {
            $json = $response->json();
            $bookList = $json['books'];
            $categories = Categories::all();
            
            foreach ($bookList as $data) {
                $category = $categories->random();
                Books::firstOrCreate(
                    ['title' => $data['title']],
                    [
                        'slug' => Str::slug($data['title']),
                        'description' => $data['summary'] ?? 'Tidak ada deskripsi.',
                        'author' => $data['author'][0] ?? 'Tidak diketahui',
                        'publisher' => $data['publisher'] ?? 'Tidak diketahui',
                        'isbn' => $data['details']['isbn'] ?? null,
                        'price' => 30000,
                        'cover_image' => $data['cover_image'] ?? null,
                        'category_id' => $category->id,
                    ]
                );
            }

            return redirect()->route('books.view');
        }

        return response()->json(['message' => 'Gagal mengambil data dari API.'], $response->status());
    }

    public function getBookById($id)
    {
        $book = Books::find($id);

        if (!$book) {
            return response()->json([
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => 'Detail buku berhasil diambil',
            'data' => $book
        ], 200);
    }

    public function showBooksView()
    {
        $bookCount = Books::count();

        if ($bookCount === 0) {
            $this->getBooks();
        }

        $books = Books::with('category')->get();
        $sliderBooks = Books::inRandomOrder()->limit(3)->get();
        $categories = Categories::all();

        return view('book.books-view', compact('books', 'sliderBooks', 'categories'));
    }

    public function getBooksJson()
    {
        $books = Books::with('category')->get();
        return response()->json($books);
    }

    public function BookDetail($slug)
    {
        $book = Books::where('slug', $slug)->firstOrFail();
        return view('book.book-detail', compact('book'));
    }
}