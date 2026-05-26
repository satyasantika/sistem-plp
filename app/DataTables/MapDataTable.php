<?php

namespace App\DataTables;

use App\Models\Map;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MapDataTable extends DataTable
{
    /**
     * Ekspresi gabungan grade/huruf PLP (tanpa alias SELECT) untuk filter & ORDER BY raw.
     */
    private function nilaiPlpConcatExpression(): string
    {
        return "CONCAT_WS(' / ', "
            ."NULLIF(TRIM(CONCAT(COALESCE(maps.grade1, ''), ' ', COALESCE(maps.letter1, ''))), ''), "
            ."NULLIF(TRIM(CONCAT(COALESCE(maps.grade2, ''), ' ', COALESCE(maps.letter2, ''))), ''), "
            ."NULLIF(TRIM(CONCAT(COALESCE(maps.grade, ''), ' ', COALESCE(maps.letter, ''))), '') "
            .')';
    }

    /**
     * Nilai gabungan kolom huruf PLP untuk sort & search di DataTables (mirror ringkas kolom pada view).
     */
    private function nilaiPlpSqlExpression(): string
    {
        return '('.$this->nilaiPlpConcatExpression().') AS nilai_plp';
    }

    private function applyTextColumnFilter(string $qualifiedColumn): \Closure
    {
        return function ($query, $keyword) use ($qualifiedColumn): void {
            if (trim((string) $keyword) === '') {
                return;
            }
            $like = '%'.$keyword.'%';
            $query->whereRaw($qualifiedColumn.' LIKE ?', [$like]);
        };
    }

    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $nilaiExpr = $this->nilaiPlpConcatExpression();

        return (new EloquentDataTable($query))
            // Yajra menambahkan prefix tabel maps.* pada nama kolom data; alias (tahun, tempat, …) tidak
            // ada di tabel maps sehingga search/sort gagal tanpa pemetaan eksplisit.
            ->filterColumn('tahun', function ($query, $keyword): void {
                if (trim((string) $keyword) === '') {
                    return;
                }
                $query->whereRaw('CAST(maps.year AS CHAR) LIKE ?', ['%'.$keyword.'%']);
            })
            ->filterColumn('tempat', $this->applyTextColumnFilter('schools.name'))
            ->filterColumn('mapel', $this->applyTextColumnFilter('subjects.name'))
            ->filterColumn('mahasiswa', $this->applyTextColumnFilter('student_user.name'))
            ->filterColumn('dosen', $this->applyTextColumnFilter('lecture_user.name'))
            ->filterColumn('guru', $this->applyTextColumnFilter('teacher_user.name'))
            ->filterColumn('nilai_plp', function ($query, $keyword) use ($nilaiExpr): void {
                if (trim((string) $keyword) === '') {
                    return;
                }
                $query->whereRaw('('.$nilaiExpr.') LIKE ?', ['%'.$keyword.'%']);
            })
            ->orderColumn('tahun', 'maps.year $1')
            ->orderColumn('tempat', 'schools.name $1')
            ->orderColumn('mapel', 'subjects.name $1')
            ->orderColumn('mahasiswa', 'student_user.name $1')
            ->orderColumn('dosen', 'lecture_user.name $1')
            ->orderColumn('guru', 'teacher_user.name $1')
            ->orderColumn('nilai_plp', '('.$nilaiExpr.') $1')
            ->addColumn('action', function ($row) {
                $action = '';
                $action .= ' <button type="button" data-id='.$row->id.' data-jenis="edit" class="btn btn-primary btn-sm my-1 action"><i class="ti-pencil"></i></button>';

                $isUsedInOtherTables =
                    DB::table('diaries')->where('map_id', $row->id)->exists() ||
                    DB::table('assessments')->where('map_id', $row->id)->exists() ||
                    DB::table('observations')->where('map_id', $row->id)->exists();

                if (!$isUsedInOtherTables) {
                    $action .= ' <button type="button" data-id='.$row->id.' data-jenis="delete" class="btn btn-danger btn-sm my-1 action"><i class="ti-trash"></i></button>';
                }
                return $action;
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Map $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Map $model): QueryBuilder
    {
        return $model->newQuery()
            ->select([
                'maps.id',
                DB::raw('maps.year AS tahun'),
                DB::raw('schools.name AS tempat'),
                DB::raw('subjects.name AS mapel'),
                DB::raw('student_user.name AS mahasiswa'),
                DB::raw('lecture_user.name AS dosen'),
                DB::raw('teacher_user.name AS guru'),
                DB::raw($this->nilaiPlpSqlExpression()),
            ])
            ->leftJoin('schools', 'schools.id', '=', 'maps.school_id')
            ->leftJoin('subjects', 'subjects.id', '=', 'maps.subject_id')
            ->leftJoin('users as student_user', 'student_user.id', '=', 'maps.student_id')
            ->leftJoin('users as lecture_user', 'lecture_user.id', '=', 'maps.lecture_id')
            ->leftJoin('users as teacher_user', 'teacher_user.id', '=', 'maps.teacher_id');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('map-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1, 'desc');

    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            Column::computed('action')
                    ->title('')
                    ->exportable(false)
                    ->printable(false)
                    ->width(60)
                    ->addClass('text-center'),
            Column::make('tahun'),
            Column::make('tempat'),
            Column::make('mapel'),
            Column::make('mahasiswa'),
            Column::make('dosen'),
            Column::make('guru'),
            Column::make('nilai_plp'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Map_' . date('YmdHis');
    }
}
