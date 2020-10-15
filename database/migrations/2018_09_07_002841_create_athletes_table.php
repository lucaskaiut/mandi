<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAthletesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('athletes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('matricula')->nullable()->unique('matricula');
            $table->string('athlete_category');
            $table->integer('company_id');
            $table->string('empresa');
            $table->boolean('active')->default(0);
            $table->boolean('deleted')->default(0);
            $table->string('name', 191);
            $table->string('position', 191)->nullable();
            $table->date('birth');
            $table->string('gender', 1);
            $table->string('email', 191)->unique();
            $table->string('address', 191);
            $table->string('cep', 10);
            $table->string('number');
            $table->string('neighborhood', 191);
            $table->string('city', 191);
            $table->string('uf', 2);
            $table->string('number_phone', 16);
            $table->string('rg', 11);
            $table->string('parents_name', 191)->nullable();
            $table->string('parents_rg', 11)->nullable();
            $table->string('parents_number_phone', 16)->nullable();
            $table->string('height');
            $table->string('weight');
            $table->boolean('phys_restriction');
            $table->string('phys_restriction_name', 191)->nullable();
            $table->boolean('body_pain');
            $table->string('body_pain_location', 191)->nullable();
            $table->boolean('faint');
            $table->string('posture_deviation_name', 191);
            $table->boolean('bone_injury');
            $table->string('bone_injury_name', 191)->nullable();
            $table->boolean('surgery');
            $table->string('surgery_name', 191)->nullable();
            $table->boolean('physical_disability');
            $table->string('physical_disability_name', 191)->nullable();
            $table->boolean('exercise');
            $table->string('exercise_name', 191)->nullable();
            $table->string('feeding', 191);
            $table->boolean('addiction');
            $table->string('addiction_name', 191)->nullable();
            $table->boolean('disease');
            $table->string('disease_name', 191)->nullable();
            $table->boolean('family_disease');
            $table->string('family_who_obs', 191)->nullable();
            $table->boolean('drug');
            $table->string('drug_name', 191)->nullable();
            $table->boolean('recent_pregnancy');
            $table->integer('pregnancy_number')->nullable();
            $table->text('obs', 65535)->nullable();
            $table->boolean('adimplente')->default(1);
            $table->string('ultimo_recibo', 4)->nullable();
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
        Schema::dropIfExists('athletes');
    }
}
