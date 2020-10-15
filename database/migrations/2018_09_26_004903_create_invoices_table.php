<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descricao');
            $table->string('documento', 32);
            $table->decimal('valor_original', 8, 2);
            $table->decimal('valor_pendente', 8, 2);
            $table->boolean('areceber');
            $table->date('vencimento');
            $table->boolean('quitada');
            $table->string('fornecedor_id', 4);
            $table->string('razao_social');
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
        Schema::dropIfExists('invoices');
    }
}
