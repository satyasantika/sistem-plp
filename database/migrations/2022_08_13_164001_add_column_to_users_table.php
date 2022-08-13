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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name');
            $table->string('subject_id')->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects')->constrained();
            $table->string('gender')->nullable(); //L,P
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('provider')->nullable();
            $table->boolean('is_pns')->nullable()->default(0);
            $table->string('golongan')->nullable();
            $table->string('npwp')->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->string('bank')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropColumn(['phone', 'provider', 'address', 'provider', 'is_pns', 'golongan', 'npwp', 'nomor_rekening', 'bank']);
        });
    }
};
