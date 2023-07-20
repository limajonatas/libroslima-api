<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserValidateRequest;

class RegisterController extends Controller
{
    public function store(UserValidateRequest $request)
    {
        return $request->all();
    }
}