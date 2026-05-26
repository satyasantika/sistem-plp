<?php

namespace App\DataTables;

use App\Models\Diary;
use App\Models\Map;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class DiaryDataTable extends DataTable
{
    private function plpOrderLabelSql(): string
    {
        return 'CASE '
            .'WHEN diaries.plp_order IS NULL THEN \'\' '
            ."WHEN diaries.plp_order = 1 THEN 'PLP 1' "
            ."WHEN diaries.plp_order = 2 THEN 'PLP 2' "
            .'ELSE CONCAT(\'PLP \', CAST(diaries.plp_order AS CHAR)) '
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
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $plpExpr = $this->plpOrderLabelSql();

        return (new EloquentDataTable($query))
            ->filterColumn('mahasiswa', $this->applyTextColumnFilter('student.name'))
            ->filterColumn('tempat', $this->applyTextColumnFilter('schools.name'))
            ->filterColumn('plp_order', function ($query, $keyword) use ($plpExpr): void {
                if (trim((string) $keyword) === '') {
                    return;
                }
                $like = '%'.$keyword.'%';
                $query->where(function ($sub) use ($like, $plpExpr): void {
                    $sub->whereRaw('('.$plpExpr.') LIKE ?', [$like]);
                    $sub->orWhereRaw('CAST(diaries.plp_order AS CHAR) LIKE ?', [$like]);
                });
            })
            ->filterColumn('day_order', function ($query, $keyword): void {
                if (trim((string) $keyword) === '') {
                    return;
                }
                $query->whereRaw('CAST(diaries.day_order AS CHAR) LIKE ?', ['%'.$keyword.'%']);
            })
            ->filterColumn('log_date', function ($query, $keyword): void {
                if (trim((string) $keyword) === '') {
                    return;
                }
                $query->whereRaw('CAST(diaries.log_date AS CHAR) LIKE ?', ['%'.$keyword.'%']);
            })
            ->filterColumn('note', $this->applyTextColumnFilter('diaries.note'))
            ->filterColumn('verified', function ($query, $keyword): void {
                if (trim((string) $keyword) === '') {
                    return;
                }
                $k = strtolower(trim((string) $keyword));
                $like = '%'.$k.'%';
                $query->where(function ($sub) use ($k, $like): void {
                    $sub->whereRaw('CAST(diaries.verified AS CHAR) LIKE ?', [$like]);
                    if ($k === 'ya' || $k === '1') {
                        $sub->orWhere('diaries.verified', 1);
                    }
                    if ($k === 'tidak' || $k === '0') {
                        $sub->orWhere('diaries.verified', 0);
                    }
                });
            })
            ->filterColumn('updated_at', function ($query, $keyword): void {
                if (trim((string) $keyword) === '') {
                    return;
                }
                $query->whereRaw('CAST(diaries.updated_at AS CHAR) LIKE ?', ['%'.$keyword.'%']);
            })
            ->orderColumn('mahasiswa', 'student.name $1')
            ->orderColumn('tempat', 'schools.name $1')
            ->orderColumn('plp_order', 'diaries.plp_order $1')
            ->orderColumn('day_order', 'diaries.day_order $1')
            ->orderColumn('log_date', 'diaries.log_date $1')
            ->orderColumn('note', 'diaries.note $1')
            ->orderColumn('verified', 'diaries.verified $1')
            ->orderColumn('updated_at', 'diaries.updated_at $1')
            ->addColumn('action', function ($row) {
                $action = '';
                $action .= ' <button type="button" data-id='.$row->id.' data-jenis="edit" class="btn btn-primary btn-sm my-1 action"><i class="ti-pencil"></i></button>';
                $action .= ' <button type="button" data-id='.$row->id.' data-jenis="delete" class="btn btn-danger btn-sm my-1 action"><i class="ti-trash"></i></button>';

                return $action;
            })
            ->editColumn('verified', fn ($row) => $row->verified ? 'Ya' : 'Tidak')
            ->editColumn('log_date', function ($row) {
                return $row->log_date?->format('d/m/Y') ?? '';
            })
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at?->format('d/m/Y H:i:s');
            })
            ->setRowId('id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): QueryBuilder
    {
        return Diary::query()
            ->select([
                'diaries.id',
                DB::raw('student.name AS mahasiswa'),
                DB::raw('schools.name AS tempat'),
                DB::raw('('.$this->plpOrderLabelSql().') AS plp_order'),
                'diaries.day_order',
                'diaries.log_date',
                'diaries.note',
                'diaries.verified',
                'diaries.updated_at',
            ])
            ->leftJoin('maps', 'maps.id', '=', 'diaries.map_id')
            ->where('maps.year', Map::activeYear())
            ->leftJoin('users as student', 'student.id', '=', 'maps.student_id')
            ->leftJoin('schools', 'schools.id', '=', 'maps.school_id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('diary-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(5, 'desc');
    }

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
            Column::make('tempat'),
            Column::make('plp_order'),
            Column::make('day_order'),
            Column::make('log_date'),
            Column::make('note'),
            Column::make('verified'),
            Column::make('updated_at'),
        ];
    }

    protected function filename(): string
    {
        return 'Diary_'.date('YmdHis');
    }
}
