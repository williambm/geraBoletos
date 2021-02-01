<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsumidoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumidores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('boleto_id');

            $table->integer('codPes')->nullable(true);
            $table->string('nome');
            $table->string('cpf');
            $table->string('cep');
            $table->string('endereco');
            $table->string('numEndereco');
            $table->string('complEndereco')->nullable(true);
            $table->string('cidade');
            $table->string('uf');
            $table->string('email');
            $table->string('telefone');
            $table->string('nomeEmpresaInstituicao')->nullable(true);
            $table->string('cnpjEmpresaInstituicao')->nullable(true);
            $table->enum('tipoSacado',['PF','PJ']);
            $table->string('rastreioBoleto_id');

            $table->timestamps();

            $table->foreign('boleto_id')->references('id')->on('boletos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consumidores');
    }
}
