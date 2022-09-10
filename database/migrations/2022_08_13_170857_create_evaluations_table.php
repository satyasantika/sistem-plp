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
            $table->integer('score1')->nullable();
            $table->integer('score2')->nullable();
            $table->integer('score3')->nullable();
            $table->integer('score4')->nullable();
            $table->integer('score5')->nullable();
            $table->integer('score6')->nullable();
            $table->integer('score7')->nullable();
            $table->integer('score8')->nullable();
            $table->integer('score9')->nullable();
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
            $table->string('item1')->nullable();
            $table->string('item2')->nullable();
            $table->string('item3')->nullable();
            $table->string('item4')->nullable();
            $table->string('item5')->nullable();
            $table->string('item6')->nullable();
            $table->string('item7')->nullable();
            $table->string('item8')->nullable();
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
