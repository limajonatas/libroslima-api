<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'title',
        'author',
        'pages',
        'publisher_year',
        'page_current',
        'time_read_total',
        'time_read_per_page',
        'synopsis',
        'image',
    ];
}
