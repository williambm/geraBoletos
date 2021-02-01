<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoletosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boletos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grupo_id');

            $table->string('nomeEvento');
            $table->integer('codFonteRecurso')->nullable(true);
            $table->string('nomeFonte')->nullable(true);
            $table->string('nomeSubFonte')->nullable(true);
            $table->string('estrutHierarq');
            $table->integer('codConvenio')->nullable(true);
            $table->date('dataVenc')->nullable(true);
            $table->decimal('valor',10,2);
            $table->decimal('desconto',10,2)->nullable(true);
            $table->text('infoSacado')->nullable(true);
            $table->string('instrObjCobranca');            
            $table->text('obsLegal');
            $table->date('iniDataPublicacao');
            $table->date('fimDataPublicacao');
            $table->integer('codUnidade');
            $table->enum('isPublicado',['sim','nao'])->default('sim');

            $table->timestamps();

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
        Schema::dropIfExists('boletos');
    }
}
