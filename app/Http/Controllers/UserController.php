<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{

    public function user()
    {
        return response()->json([
            'status' => 'success',
            'user' => auth()->user()
        ]);
    }
}