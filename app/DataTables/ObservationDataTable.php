<?php

namespace App\DataTables;

use App\Models\Map;
use App\Models\Observation;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ObservationDataTable extends DataTable
{
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
        return (new EloquentDataTable($query))
            ->filterColumn('form', $this->applyTextColumnFilter('forms.id'))
            ->filterColumn('mahasiswa', $this->applyTextColumnFilter('student.name'))
            ->filterColumn('tempat', $this->applyTextColumnFilter('schools.name'))
            ->filterColumn('updated_at', function ($query, $keyword): void {
                if (trim((string) $keyword) === '') {
                    return;
                }
                $query->whereRaw('CAST(observations.updated_at AS CHAR) LIKE ?', ['%'.$keyword.'%']);
            })
            ->orderColumn('form', 'forms.id $1')
            ->orderColumn('mahasiswa', 'student.name $1')
            ->orderColumn('tempat', 'schools.name $1')
            ->orderColumn('updated_at', 'observations.updated_at $1')
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
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): QueryBuilder
    {
        return Observation::query()
            ->select([
                'observations.id',
                DB::raw('CAST(forms.id AS CHAR) AS form'),
                DB::raw('student.name AS mahasiswa'),
                DB::raw('schools.name AS tempat'),
                'observations.updated_at',
            ])
            ->leftJoin('maps', 'maps.id', '=', 'observations.map_id')
            ->where('maps.year', Map::activeYear())
            ->leftJoin('users as student', 'student.id', '=', 'maps.student_id')
            ->leftJoin('schools', 'schools.id', '=', 'maps.school_id')
            ->leftJoin('forms', 'forms.id', '=', 'observations.form_id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('observation-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(4, 'asc');
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
            Column::make('form'),
            Column::make('mahasiswa'),
            Column::make('tempat'),
            Column::make('updated_at'),
        ];
    }

    protected function filename(): string
    {
        return 'Observation_'.date('YmdHis');
    }
}
