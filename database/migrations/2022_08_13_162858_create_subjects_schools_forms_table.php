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
    }
};
