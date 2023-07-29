<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BookValidateRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Read;

class BookController extends Controller
{
    public function index()
    {
        try {
            $books = auth()->user()->books;
            $books->load('authors:name,last_name');
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
            $authorsIds = [];
            foreach ($request->authors as $authorFullName) {
                $authorData = explode(' ', $authorFullName, 2);
                $name = $authorData[0];
                $lastName = isset($authorData[1]) ? $authorData[1] : null;

                $author = Author::firstOrCreate([
                    'name' => $name,
                    'last_name' => $lastName
                ]);

                $authorsIds[] = $author->id;
            }

            $existingBook = Book::where('title', $request->title)
                ->where('id_user', auth()->user()->id)->whereHas('authors', function ($query) use ($authorsIds) {
                    $query->whereIn('authors.id', $authorsIds);
                })->first();

            if ($existingBook) {
                return response()->json([
                    'message' => 'Livro já cadastrado!',
                    'status' => 'error'
                ], 404);
            } else {
                $book = new Book();
                $book->id_user = auth()->user()->id;
                $book->title = $request->title;
                $book->pages = $request->pages;
                $book->how_many_times_read = $request->how_many_times_read;
                $book->publisher_year = $request->publisher_year;
                $book->genre = $request->genre;
                $book->page_current = $request->page_current;
                $book->synopsis = $request->synopsis;
                $book->image = $request->image;

                $book->save();

                $book->authors()->attach($authorsIds);
                $book->load('authors');
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
                $currentAuthors = $book->authors;

                $requestedAuthorIds = [];

                foreach ($request->authors as $author) {
                    $authorData = explode(' ', $author, 2);
                    $name = $authorData[0];
                    $lastName = isset($authorData[1]) ? $authorData[1] : null;

                    $existingAuthor = Author::where('name', $name)->where('last_name', $lastName)->first();

                    //add authors that already exists and new authors
                    if ($existingAuthor) {
                        $requestedAuthorIds[] = $existingAuthor->id;
                    } else {
                        $newAuthor = Author::create([
                            'name' => $name,
                            'last_name' => $lastName
                        ]);

                        $requestedAuthorIds[] = $newAuthor->id;
                    }
                }

                //compare currents authors with the new authors and delete the that don't have books associate
                $currentAuthorIds = $currentAuthors->pluck('id')->toArray();
                $authorsToRemove = array_diff($currentAuthorIds, $requestedAuthorIds);
                foreach ($authorsToRemove as $authorId) {
                    $existingAuthor = Author::find($authorId);
                    if ($existingAuthor) {
                        if ($existingAuthor->books->count() == 1) {
                            $existingAuthor->delete();
                        }
                    }
                }

                $book->title = $request->title;
                $book->pages = $request->pages;
                $book->how_many_times_read = $request->how_many_times_read;
                $book->publisher_year = $request->publisher_year;
                $book->genre = $request->genre;
                $book->page_current = $request->page_current;
                $book->synopsis = $request->synopsis;
                $book->image = $request->image;
                $book->save();

                $book->authors()->sync($requestedAuthorIds);
                $book->load('authors');
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
            $book = Book::find($id);
            if ($book) {
                $book->reads;
                $book->authors;
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
                $authors = $book->authors;

                //verify if author is not associated with another book
                foreach ($authors as $author) {
                    if ($author->books->count() == 1) {
                        $author->delete();
                    }
                }
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
