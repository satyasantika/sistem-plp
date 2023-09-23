<?php

namespace App\DataTables;

use App\Models\Map;
use App\Models\Assessment;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class AssessmentDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function($row){
                $action = '';
                $action .= ' <button type="button" data-id='.$row->id.' data-jenis="edit" class="btn btn-primary btn-sm my-1 action"><i class="ti-pencil"></i></button>';
                $action .= ' <button type="button" data-id='.$row->id.' data-jenis="delete" class="btn btn-danger btn-sm my-1 action"><i class="ti-trash"></i></button>';
                return $action;
            })
            ->addColumn('mahasiswa', function($row){
                if (isset($row->map_id)) {
                    return $row->maps->students->name;
                }
            })
            ->addColumn('sekolah', function($row){
                if (isset($row->map_id)) {
                    return $row->maps->schools->name;
                }
            })
            ->editColumn('updated_at', function($row) {
                return $row->updated_at->format('d/m/Y H:i:s');
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Assessment $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Assessment $model): QueryBuilder
    {
        $maps = Map::where('year',2023)->pluck('id');
        return $model->whereIn('map_id',$maps)->newQuery();
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
                    ->orderBy(1, 'asc');
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
            Column::make('assessor'),
            Column::make('plp_order'),
            Column::make('form_id'),
            Column::make('form_order')->title('ke-'),
            Column::make('grade'),
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
        return 'Assessment_' . date('YmdHis');
    }
}
