<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cashier_id')->unsigned()->nullable();
            $table->foreign('cashier_id')->references('id')->on('cashiers')->onDelete('set null');
            $table->integer('abertura_id')->unsigned()->nullable();
            $table->foreign('abertura_id')->references('id')->on('cash_flows')->onDelete('set null');
            $table->string('referencia');
            $table->string('documento');
            $table->string('descricao');
            $table->decimal('valor', 10, 2);
            $table->boolean('entrada');
            $table->string('pag_tipo');
            $table->string('pag_tipo_categoria');
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
        Schema::dropIfExists('cash_histories');
    }
}
