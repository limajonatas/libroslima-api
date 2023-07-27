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
        Schema::table('reads', function (Blueprint $table) {
            $table->double('time_read', 10, 2)->change();
            $table->double('time_read_per_page', 10, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reads', function (Blueprint $table) {
            $table->double('time_read')->change();
            $table->double('time_read_per_page')->change();
        });
    }
};