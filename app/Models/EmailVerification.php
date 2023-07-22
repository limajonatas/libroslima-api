<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    use HasFactory;

    protected $table = 'email_verification';

    protected $fillable = [
        'name_user',
        'nickname_user',
        'email_user',
        'code_verification',
        'password',
    ];
}