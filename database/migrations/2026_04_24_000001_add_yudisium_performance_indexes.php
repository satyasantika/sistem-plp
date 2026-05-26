<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
    $mapsIndexes = [
      'maps_yudisium_plp1_subject_student_idx',
      'maps_yudisium_plp2_subject_student_idx',
      'maps_yudisium_plp_subject_student_idx',
      'maps_yudisium_plp1_letter_idx',
      'maps_yudisium_plp2_letter_idx',
      'maps_yudisium_plp_letter_idx',
      'maps_yudisium_plp1_grade_idx',
      'maps_yudisium_plp2_grade_idx',
      'maps_yudisium_plp_grade_idx',
    ];
    foreach ($mapsIndexes as $indexName) {
      $this->dropIndexIfExists('maps', $indexName);
    }

    // Indeks pada assessments yang diawali map_id bisa dipakai InnoDB sebagai indeks FK map_id → maps.
    // Drop indeks bisa gagal (1553); drop FK sementara lalu recreate.
    Schema::table('assessments', function (Blueprint $table) {
      $table->dropForeign(['map_id']);
    });

    foreach (
      [
        'assessments_map_plp_assessor_form_idx',
        'assessments_plp_assessor_form_idx',
        'assessments_map_assessor_idx',
      ] as $indexName
    ) {
      $this->dropIndexIfExists('assessments', $indexName);
    }

    Schema::table('assessments', function (Blueprint $table) {
      $table->foreign('map_id')->references('id')->on('maps');
    });
  }

  /**
   * Idempotent rollback: indeks bisa sudah hilang karena DDL terpotong atau perubahan manual.
   */
  private function dropIndexIfExists(string $table, string $indexName): void
  {
    if (!$this->indexExists($table, $indexName)) {
      return;
    }

    Schema::table($table, function (Blueprint $table) use ($indexName): void {
      $table->dropIndex($indexName);
    });
  }

  private function indexExists(string $table, string $indexName): bool
  {
    $connection = Schema::getConnection();
    $driver = $connection->getDriverName();
    $fullTable = $connection->getTablePrefix().$table;

    if ($driver === 'sqlite') {
      return DB::connection($connection->getName())->table('sqlite_master')
        ->where('type', 'index')
        ->where('tbl_name', $fullTable)
        ->where('name', $indexName)
        ->exists();
    }

    if (!in_array($driver, ['mysql', 'mariadb'], true)) {
      throw new \RuntimeException(
        "Migration 2026_04_24_000001 add_yudisium_performance_indexes: dropIndexIfExists not supported for driver [{$driver}]"
      );
    }

    $dbName = $connection->getDatabaseName();

    return (bool) DB::connection($connection->getName())->table('information_schema.statistics')
      ->where('table_schema', $dbName)
      ->where('table_name', $fullTable)
      ->where('index_name', $indexName)
      ->exists();
  }
};
