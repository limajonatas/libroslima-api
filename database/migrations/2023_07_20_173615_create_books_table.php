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
            $table->integer('pages');
            $table->integer('how_many_times_read')->default(0);

            //opcionais
            $table->integer('publisher_year')->nullable();
            $table->string('genre')->nullable();
            $table->integer('page_current')->nullable()->default(0);
            $table->integer('pages_read')->nullable()->default(0);
            $table->double('accumulated_read_time', 10, 2)->nullable()->default(0)->change();
            $table->double('time_read_total', 10, 2)->nullable()->default(0)->change();
            $table->double('time_read_per_page', 10, 2)->nullable()->default(0)->change();
            $table->text('synopsis')->nullable();
            $table->string('image')->nullable();
            $table->integer('rating')->nullable();
            $table->text('opinion')->nullable();
            $table->timestamp('read_start_date')->nullable();
            $table->timestamp('read_end_date')->nullable();
            $table->date('last_read_complete')->nullable();

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
