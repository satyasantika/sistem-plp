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
        Schema::create('coordinator_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained();
            $table->string('coordinator_name');
            $table->timestamps();
        });
        Schema::create('teacher_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained();
            $table->string('teacher_name');
            $table->string('subject_id')->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects')->constrained();
            $table->integer('class_count')->nullable();
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
        Schema::dropIfExists('coordinator_proposals');
        Schema::dropIfExists('teacher_proposals');
    }
};
