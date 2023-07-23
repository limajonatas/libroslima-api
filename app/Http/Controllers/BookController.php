<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BookValidateRequest;
use App\Models\Book;

class BookController extends Controller
{
    public function store(BookValidateRequest $request)
    {
        //se o livro já estiver no banco (título e autor) não pode ser cadastrado
        try {
            $book = Book::where('title', $request->title)->where('author', $request->author)->first();

            if ($book) {
                return response()->json([
                    'message' => 'Livro já cadastrado!',
                    'status' => 'error'
                ], 404);
            } else {

                $book = new Book();
                $book->id_user = auth()->user()->id;
                $book->title = $request->title;
                $book->author = $request->author;
                $book->pages = $request->pages;
                $book->how_many_times_read = $request->how_many_times_read;
                $book->publisher_year = $request->publisher_year;
                $book->genre = $request->genre;
                $book->page_current = $request->page_current;
                $book->synopsis = $request->synopsis;
                $book->image = $request->image;
                $book->save();
                return response()->json([
                    'message' => 'Livro cadastrado com sucesso!',
                    'status' => 'success',
                    'book' => $book
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao cadastrar livro!',
                'status' => 'error',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}