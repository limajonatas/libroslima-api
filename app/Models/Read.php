<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Read extends Model
{
    use HasFactory;

    protected $fillable =[
        'id_book',
        'timestamp',
        'time_read',
        'stopped_page',
        'pages_read',
        'time_read_per_page',
        'comments',
    ];
}