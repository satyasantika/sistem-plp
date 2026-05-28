<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plp_final_grade_form_rules', function (Blueprint $table): void {
            $table->unsignedTinyInteger('times')->default(1)->after('form_id');
        });
    }

    public function down(): void
    {
        Schema::table('plp_final_grade_form_rules', function (Blueprint $table): void {
            $table->dropColumn('times');
        });
    }
};
