<?php

namespace App\DataTables;

use App\Models\School;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SchoolDataTable extends DataTable
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
                $action .= ' <button type="button" data-id='.$row->id.' data-jenis="edit" class="btn btn-primary btn-sm action"><i class="ti-pencil"></i></button>';
                $action .= ' <button type="button" data-id='.$row->id.' data-jenis="delete" class="btn btn-danger btn-sm action"><i class="ti-trash"></i></button>';
                return $action;
            })
            ->addColumn('headmaster', function($row){
                if (isset($row->headmaster_id)) {
                    return $row->headmasters->name;
                }
            })
            ->addColumn('coordinator', function($row){
                if (isset($row->coordinator_id)) {
                    return $row->coordinators->name;
                }
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\School $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(School $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('school-table')
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
            Column::make('name')->orderable(true),
            Column::make('address')->title('alamat'),
            Column::make('headmaster')->title('kepala'),
            Column::make('coordinator')->title('koord.GP'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'School_' . date('YmdHis');
    }
}
