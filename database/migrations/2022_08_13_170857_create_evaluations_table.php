<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('map_id')->constrained();
            $table->string('assessor'); // enum(dosen,guru)
            $table->integer('plp_order');
            $table->string('form_id')->nullable();
            $table->foreign('form_id')->references('id')->on('forms')->constrained();
            $table->integer('form_order');
            $table->integer('score01')->nullable();
            $table->integer('score02')->nullable();
            $table->integer('score03')->nullable();
            $table->integer('score04')->nullable();
            $table->integer('score05')->nullable();
            $table->integer('score06')->nullable();
            $table->integer('score07')->nullable();
            $table->integer('score08')->nullable();
            $table->integer('score09')->nullable();
            $table->integer('grade')->nullable();
            $table->longText('note')->nullable();
            $table->text('exam_thema')->nullable();
            $table->text('exam_subthema')->nullable();
            $table->string('exam_class')->nullable();
            $table->date('exam_date')->nullable();
            $table->timestamps();
        });
        Schema::create('observations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('map_id')->constrained();
            $table->string('form_id')->nullable();
            $table->foreign('form_id')->references('id')->on('forms')->constrained();
            $table->string('item01')->nullable();
            $table->string('item02')->nullable();
            $table->string('item03')->nullable();
            $table->string('item04')->nullable();
            $table->string('item05')->nullable();
            $table->string('item06')->nullable();
            $table->string('item07')->nullable();
            $table->string('item08')->nullable();
            $table->longText('note')->nullable();
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
        Schema::dropIfExists('assessments');
        Schema::dropIfExists('observations');
    }
};
