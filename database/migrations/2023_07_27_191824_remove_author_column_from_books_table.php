<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveAuthorColumnFromBooksTable extends Migration
{
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('author');
        });
    }

    public function down()
    {
        // Se você precisar desfazer a remoção da coluna, pode implementar o código aqui.
        // No entanto, como a remoção de uma coluna é uma ação irreversível em alguns bancos de dados,
        // é recomendado criar uma nova migração separada se precisar adicionar a coluna novamente.
    }
}
