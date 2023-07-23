<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{

    public function user()
    {
        return response()->json([
            'user' => auth()->user(),
            'status' => 'success'
        ]);
    }
}
