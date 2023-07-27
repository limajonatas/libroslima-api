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

    public function reads()
    {
        return $this->hasMany(Read::class, 'id_book')->orderBy('timestamp', 'desc');
    }

    public function authors(){
        return $this->belongsToMany(Author::class, 'author_book', 'id_book', 'id_author');
    }

    protected $fillable = [
        'id_user',
        'title',
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