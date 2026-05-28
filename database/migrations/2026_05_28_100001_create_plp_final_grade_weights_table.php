<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plp_final_grade_weights', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('year');
            $table->unsignedTinyInteger('plp_order');
            $table->decimal('dosen_weight', 8, 4)->default(0.4);
            $table->decimal('guru_weight', 8, 4)->default(0.6);
            $table->timestamps();

            $table->unique(['year', 'plp_order'], 'plp_fg_weights_year_plp_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plp_final_grade_weights');
    }
};
