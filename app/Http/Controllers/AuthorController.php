<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        try{
            //retornar todos os autores do usuário logado
            $authors = auth()->user()->books->load('authors:name,last_name')->pluck('authors')->flatten()->values();

            return response()->json([
                'status' => 'success',
                'authors' => $authors
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Erro ao buscar autores!',
                'status' => 'error',
                'error' => $e->getMessage()
            ], 404);
        }
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(Author $author)
    {
        //
    }


    public function edit(Author $author)
    {
        //
    }


    public function update(Request $request, Author $author)
    {
        //
    }

    public function destroy(Author $author)
    {
        //
    }
}
