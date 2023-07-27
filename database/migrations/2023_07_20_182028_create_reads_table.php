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
        Schema::create('reads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_book');
            $table->foreign('id_book')->references('id')->on('books')->onUpdate('cascade')->onDelete('cascade');

            $table->timestamp('timestamp');
            $table->integer('stopped_page');
            $table->integer('pages_read');
            $table->double('time_read', 10, 2)->change();
            $table->double('time_read_per_page', 10, 2)->change();

            $table->string('section_where_stopped')->nullable();
            $table->string('comments')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reads');
    }
};
