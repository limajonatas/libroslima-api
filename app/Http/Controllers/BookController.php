<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BookValidateRequest;
use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        try {
            $books = Book::where('id_user', auth()->user()->id)->get();
            return response()->json([
                'status' => 'success',
                'books' => $books
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar livros!',
                'status' => 'error',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function store(BookValidateRequest $request)
    {
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

    public function update(BookValidateRequest $request)
    {
        try {
            $book = Book::where('id', $request->id)->where('id_user', auth()->user()->id)->first();
            if ($book) {
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
                    'message' => 'Livro atualizado com sucesso!',
                    'status' => 'success',
                    'book' => $book
                ]);
            } else {
                return response()->json([
                    'message' => 'Livro não encontrado!',
                    'status' => 'error'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar livro!',
                'status' => 'error',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function show(string $id)
    {
        try {
            $book = Book::where('id', $id)->where('id_user', auth()->user()->id)->first();
            if ($book) {
                return response()->json([
                    'message' => 'Livro encontrado!',
                    'status' => 'success',
                    'book' => $book
                ]);
            } else {
                return response()->json([
                    'message' => 'Livro não encontrado!',
                    'status' => 'error'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar livro!',
                'status' => 'error',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function destroy(string $id)
    {
        try {
            $book = Book::where('id', $id)->where('id_user', auth()->user()->id)->first();
            if ($book) {
                $book->delete();
                return response()->json([
                    'message' => 'Livro excluído com sucesso!',
                    'status' => 'success'
                ]);
            } else {
                return response()->json([
                    'message' => 'Livro não encontrado!',
                    'status' => 'error'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir livro!',
                'status' => 'error',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}