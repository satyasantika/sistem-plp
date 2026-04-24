<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::table('maps', function (Blueprint $table) {
      $table->index(['year', 'plp1', 'subject_id', 'student_id'], 'maps_yudisium_plp1_subject_student_idx');
      $table->index(['year', 'plp2', 'subject_id', 'student_id'], 'maps_yudisium_plp2_subject_student_idx');
      $table->index(['year', 'plp', 'subject_id', 'student_id'], 'maps_yudisium_plp_subject_student_idx');

      $table->index(['year', 'plp1', 'letter1'], 'maps_yudisium_plp1_letter_idx');
      $table->index(['year', 'plp2', 'letter2'], 'maps_yudisium_plp2_letter_idx');
      $table->index(['year', 'plp', 'letter'], 'maps_yudisium_plp_letter_idx');

      $table->index(['year', 'plp1', 'grade1'], 'maps_yudisium_plp1_grade_idx');
      $table->index(['year', 'plp2', 'grade2'], 'maps_yudisium_plp2_grade_idx');
      $table->index(['year', 'plp', 'grade'], 'maps_yudisium_plp_grade_idx');
    });

    Schema::table('assessments', function (Blueprint $table) {
      $table->index(['map_id', 'plp_order', 'assessor', 'form_id'], 'assessments_map_plp_assessor_form_idx');
      $table->index(['plp_order', 'assessor', 'form_id'], 'assessments_plp_assessor_form_idx');
      $table->index(['map_id', 'assessor'], 'assessments_map_assessor_idx');
    });
  }

  public function down(): void
  {
    Schema::table('maps', function (Blueprint $table) {
      $table->dropIndex('maps_yudisium_plp1_subject_student_idx');
      $table->dropIndex('maps_yudisium_plp2_subject_student_idx');
      $table->dropIndex('maps_yudisium_plp_subject_student_idx');

      $table->dropIndex('maps_yudisium_plp1_letter_idx');
      $table->dropIndex('maps_yudisium_plp2_letter_idx');
      $table->dropIndex('maps_yudisium_plp_letter_idx');

      $table->dropIndex('maps_yudisium_plp1_grade_idx');
      $table->dropIndex('maps_yudisium_plp2_grade_idx');
      $table->dropIndex('maps_yudisium_plp_grade_idx');
    });

    Schema::table('assessments', function (Blueprint $table) {
      $table->dropIndex('assessments_map_plp_assessor_form_idx');
      $table->dropIndex('assessments_plp_assessor_form_idx');
      $table->dropIndex('assessments_map_assessor_idx');
    });
  }
};
