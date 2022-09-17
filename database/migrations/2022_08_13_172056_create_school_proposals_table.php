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
        // Schema::create('coordinator_proposals', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('school_id')->constrained();
        //     $table->string('coordinator_name');
        //     $table->boolean('registered')->default(0);
        //     $table->timestamps();
        // });
        Schema::create('school_user_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained();
            $table->string('name')->nullable();
            $table->string('role')->nullable(); // enum(guru,korgur)
            $table->string('nip')->nullable();
            $table->string('phone')->nullable();
            $table->string('subject_id')->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects')->constrained();
            $table->integer('class_count')->nullable();
            $table->boolean('registered')->default(0);
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
        // Schema::dropIfExists('coordinator_proposals');
        // Schema::dropIfExists('teacher_proposals');
        Schema::dropIfExists('school_user_proposals');
    }
};
