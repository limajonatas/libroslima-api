<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('author_book', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_book');
            $table->unsignedBigInteger('id_author');
            $table->timestamps();

            $table->foreign('id_book')->references('id')->on('books')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_author')->references('id')->on('authors')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('author_book');
    }
};
