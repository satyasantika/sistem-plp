<?php

namespace App\DataTables;

use App\Models\Map;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MapDataTable extends DataTable
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
            // ->editColumn('updated_at', function($row) {
            //     return $row->updated_at->format('d/m/Y H:i:s');
            // })
            ->addColumn('action', function($row){
                $action = '';
                $action .= ' <button type="button" data-id='.$row->id.' data-jenis="edit" class="btn btn-primary btn-sm my-1 action"><i class="ti-pencil"></i></button>';
                $action .= ' <button type="button" data-id='.$row->id.' data-jenis="delete" class="btn btn-danger btn-sm my-1 action"><i class="ti-trash"></i></button>';
                return $action;
            })
            ->addColumn('Mahasiswa', function($row){
                if (isset($row->student_id)) {
                    return $row->students->name;
                }
            })
            ->addColumn('DPL', function($row){
                if (isset($row->lecture_id)) {
                    return $row->lectures->name;
                }
            })
            ->addColumn('GP', function($row){
                if (isset($row->teacher_id)) {
                    return $row->teachers->name;
                }
            })
            ->addColumn('Sekolah', function($row){
                if (isset($row->school_id)) {
                    return $row->schools->name;
                }
            })
            ->addColumn('Mapel', function($row){
                if (isset($row->subject_id)) {
                    return $row->subjects->name;
                }
            })
            ->addColumn('year', function($row){
                if (isset($row->year)) {
                    return $row->year;
                }
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
        return $model->where('year',2023)->newQuery();
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
            Column::computed('Sekolah')->orderable(true)->searchable(true),
            Column::computed('Mapel')->orderable(true)->searchable(true),
            Column::computed('Mahasiswa')->orderable(true)->searchable(true),
            Column::computed('DPL')->orderable(true)->searchable(true),
            Column::computed('GP')->orderable(true)->searchable(true),
            // Column::make('plp1'),
            // Column::make('plp2'),
            Column::make('year')->title('Tahun'),
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
