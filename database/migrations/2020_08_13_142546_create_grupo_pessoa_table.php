<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrupoPessoaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupo_pessoa', function (Blueprint $table) {
            $table->unsignedBigInteger('pessoa_id');
            $table->unsignedBigInteger('grupo_id');

            //As FK
            $table->foreign('pessoa_id')->references('id')->on('pessoas');
            $table->foreign('grupo_id')->references('id')->on('grupos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grupo_pessoa');
    }
}
