<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plp_final_grade_form_rules', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('year');
            $table->unsignedTinyInteger('plp_order');
            $table->string('assessor', 16);
            $table->string('form_id');
            $table->timestamps();

            $table->foreign('form_id')->references('id')->on('forms')->cascadeOnDelete();
            $table->unique(['year', 'plp_order', 'assessor', 'form_id'], 'plp_fg_rules_unique');
            $table->index(['year', 'plp_order', 'assessor'], 'plp_fg_rules_lookup_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plp_final_grade_form_rules');
    }
};
