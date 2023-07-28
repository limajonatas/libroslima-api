<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    public function books()
    {
        return $this->belongsToMany(Book::class, 'author_book', 'id_author', 'id_book');
    }

    protected $fillable = [
        'name',
        'last_name',
    ];
}