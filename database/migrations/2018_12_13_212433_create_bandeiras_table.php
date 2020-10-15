<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBandeirasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bandeiras', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->enum('tipo', ['credito', 'debito']);
            $table->decimal('taxa', 3,2);
            $table->integer('dias');
            $table->integer('card_id');
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
        Schema::dropIfExists('bandeiras');
    }
}
