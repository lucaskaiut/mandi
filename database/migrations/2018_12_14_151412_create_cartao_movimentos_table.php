<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartaoMovimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cartao_movimentos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('CodigoOperadora');
            $table->integer('bandeira_id');
            $table->string('bandeira');
            $table->enum('tipo', ['credito', 'debito']);
            $table->boolean('entrada');
            $table->integer('cv');
            $table->integer('NParcelas');
            $table->integer('parcela');
            $table->decimal('valor',11, 2);
            $table->decimal('taxa', 4, 2);
            $table->decimal('valor_liquido', 11, 2);
            $table->date('previsao');
            $table->boolean('liquidado')->default(0);
            $table->date('dataliquidado')->nullable();
            $table->string('documento');
            $table->boolean('recibo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cartao_movimentos');
    }
}
