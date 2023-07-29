<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBooksTableColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->double('accumulated_read_time', 10, 2)->nullable()->default(0)->change();
            $table->double('time_read_total', 10, 2)->nullable()->default(0)->change();
            $table->double('time_read_per_page', 10, 2)->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->decimal('accumulated_read_time')->nullable()->default(0)->change();
            $table->decimal('time_read_total')->nullable()->default(0)->change();
            $table->decimal('time_read_per_page')->nullable()->default(0)->change();
        });
    }
}
