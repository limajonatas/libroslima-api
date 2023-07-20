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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->string('title');
            $table->string('author');
            $table->integer('pages');

            //opcionais
            $table->integer('publisher_year')->nullable();
            $table->integer('page_current')->nullable()->default(0);
            $table->integer('time_read_total')->nullable()->default(0);
            $table->integer('time_read_per_page')->nullable()->default(0);
            $table->text('synopsis')->nullable();
            $table->string('image')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};