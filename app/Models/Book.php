<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function Ramsey\Uuid\v1;

class Book extends Model
{
    use HasFactory;
    protected $table = 'books';

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
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
        'how_many_times_read',
        'genre'
    ];
}