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
        Schema::create('maps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->nullabel();
            $table->foreignId('lecture_id')->constrained('users')->nullabel();
            $table->foreignId('teacher_id')->constrained('users')->nullabel();
            $table->foreignId('school_id')->constrained()->nullabel();
            $table->integer('year')->nullable();
            $table->boolean('plp1')->default(1);
            $table->boolean('plp2')->default(1);
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
        Schema::dropIfExists('maps');
    }
};
