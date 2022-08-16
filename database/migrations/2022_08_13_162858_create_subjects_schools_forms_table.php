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
        Schema::create('subjects', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('departement');
        });
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->foreignId('headmaster_id')->nullable();
            $table->foreignId('coordinator_id')->nullable();
        });
        Schema::create('forms', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('type')->nullable();
            $table->integer('count')->default(0);
            $table->integer('max_score')->default(0);
        });
        Schema::create('form_items', function (Blueprint $table) {
            $table->id();
            $table->string('form_id')->nullable();
            $table->foreign('form_id')->references('id')->on('forms')->constrained();
            $table->string('component')->nullable(); //petunjuk, item
            $table->integer('component_order')->nullable();
            $table->string('name')->nullable();
            $table->integer('max_score')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('schools');
        Schema::dropIfExists('forms');
        Schema::dropIfExists('form_items');
    }
};
