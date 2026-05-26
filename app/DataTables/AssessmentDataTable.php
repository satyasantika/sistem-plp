<?php

namespace App\DataTables;

use App\Models\Assessment;
use App\Models\Map;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class AssessmentDataTable extends DataTable
{
    /**
     * Nama penilai sesuai jenis penilai pada map (guru pamong / dosen).
     */
    private function penilaiNameSql(): string
    {
        return 'CASE '
            ."WHEN assessments.assessor = 'guru' THEN COALESCE(teacher.name, '') "
            ."WHEN assessments.assessor = 'dosen' THEN COALESCE(lecturer.name, '') "
            ."ELSE '' "
            .'END';
    }

    private function plpLabelSql(): string
    {
        return 'CASE assessments.plp_order '
            ."WHEN 1 THEN 'PLP 1' "
            ."WHEN 2 THEN 'PLP 2' "
            ."ELSE CONCAT('PLP ', CAST(assessments.plp_order AS CHAR)) "
            .'END';
    }

    private function applyTextColumnFilter(string $qualifiedColumn): \Closure
    {
        return function ($query, $keyword) use ($qualifiedColumn): void {
            if (trim((string) $keyword) === '') {
                return;
            }
            $query->whereRaw($qualifiedColumn.' LIKE ?', ['%'.$keyword.'%']);
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
        $penilaiName = $this->penilaiNameSql();
        $plpLabel = $this->plpLabelSql();

        return (new EloquentDataTable($query))
            ->filterColumn('mahasiswa', $this->applyTextColumnFilter('student.name'))
            ->filterColumn('penilai', function ($query, $keyword) use ($penilaiName): void {
                if (trim((string) $keyword) === '') {
                    return;
                }
                $like = '%'.$keyword.'%';
                $query->whereRaw('('.$penilaiName.') LIKE ?', [$like]);
            })
            ->filterColumn('plp', function ($query, $keyword) use ($plpLabel): void {
                if (trim((string) $keyword) === '') {
                    return;
                }
                $like = '%'.$keyword.'%';
                $query->where(function ($sub) use ($like, $plpLabel): void {
                    $sub->whereRaw('('.$plpLabel.') LIKE ?', [$like]);
                    $sub->orWhereRaw('CAST(assessments.plp_order AS CHAR) LIKE ?', [$like]);
                });
            })
            ->filterColumn('form', $this->applyTextColumnFilter('forms.id'))
            ->filterColumn('ke', function ($query, $keyword): void {
                if (trim((string) $keyword) === '') {
                    return;
                }
                $query->whereRaw('CAST(assessments.form_order AS CHAR) LIKE ?', ['%'.$keyword.'%']);
            })
            ->filterColumn('nilai', function ($query, $keyword): void {
                if (trim((string) $keyword) === '') {
                    return;
                }
                $query->whereRaw('CAST(assessments.grade AS CHAR) LIKE ?', ['%'.$keyword.'%']);
            })
            ->filterColumn('updated_at', function ($query, $keyword): void {
                if (trim((string) $keyword) === '') {
                    return;
                }
                $query->whereRaw('CAST(assessments.updated_at AS CHAR) LIKE ?', ['%'.$keyword.'%']);
            })
            ->orderColumn('mahasiswa', 'student.name $1')
            ->orderColumn('penilai', '('.$this->penilaiNameSql().') $1')
            ->orderColumn('plp', 'assessments.plp_order $1')
            ->orderColumn('form', 'forms.id $1')
            ->orderColumn('ke', 'assessments.form_order $1')
            ->orderColumn('nilai', 'assessments.grade $1')
            ->orderColumn('updated_at', 'assessments.updated_at $1')
            ->addColumn('action', function ($row) {
                $action = '';
                $action .= ' <button type="button" data-id='.$row->id.' data-jenis="edit" class="btn btn-primary btn-sm my-1 action"><i class="ti-pencil"></i></button>';
                $action .= ' <button type="button" data-id='.$row->id.' data-jenis="delete" class="btn btn-danger btn-sm my-1 action"><i class="ti-trash"></i></button>';

                return $action;
            })
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at?->format('d/m/Y H:i:s');
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): QueryBuilder
    {
        return Assessment::query()
            ->select([
                'assessments.id',
                DB::raw('student.name AS mahasiswa'),
                DB::raw('('.$this->penilaiNameSql().') AS penilai'),
                DB::raw('('.$this->plpLabelSql().') AS plp'),
                DB::raw('CAST(forms.id AS CHAR) AS form'),
                DB::raw('assessments.form_order AS ke'),
                DB::raw('assessments.grade AS nilai'),
                'assessments.updated_at',
            ])
            ->leftJoin('maps', 'maps.id', '=', 'assessments.map_id')
            ->where('maps.year', Map::activeYear())
            ->leftJoin('users as student', 'student.id', '=', 'maps.student_id')
            ->leftJoin('users as teacher', 'teacher.id', '=', 'maps.teacher_id')
            ->leftJoin('users as lecturer', 'lecturer.id', '=', 'maps.lecture_id')
            ->leftJoin('forms', 'forms.id', '=', 'assessments.form_id');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('assessment-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(7, 'desc');
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
            Column::make('mahasiswa'),
            Column::make('penilai'),
            Column::make('plp'),
            Column::make('form'),
            Column::make('ke'),
            Column::make('nilai'),
            Column::make('updated_at'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Assessment_'.date('YmdHis');
    }
}
