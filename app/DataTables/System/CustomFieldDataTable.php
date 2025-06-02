<?php

namespace App\DataTables\System;

use App\Models\CustomField;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class CustomFieldDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['label', 'is_required', 'is_visible_in_table', 'action'])
            ->editColumn('label', function (CustomField $customField) {
                return '<a href="javascript:void(0);" '
                    . 'data-kt-custom-field-id="' . $customField->id . '" '
                    . 'data-bs-toggle="modal" '
                    . 'data-bs-target="#kt_modal_add_custom_field" '
                    . 'data-kt-action="update_row">'
                    . '<strong>' . e($customField->label) . '</strong>'
                    . '</a>';
            })
            ->editColumn('module', function (CustomField $customField) {
                return e($customField->module);
            })
            ->editColumn('name', function (CustomField $customField) {
                return e($customField->name);
            })
            ->editColumn('type', function (CustomField $customField) {
                return e($customField->type);
            })
            ->editColumn('is_required', function (CustomField $customField) {
                return $customField->is_required ? '<span class="badge badge-light-success">Yes</span>' : '<span class="badge badge-light-danger">No</span>';
            })
            ->editColumn('is_visible_in_table', function (CustomField $customField) {
                return $customField->is_visible_in_table ? '<span class="badge badge-light-success">Yes</span>' : '<span class="badge badge-light-danger">No</span>';
            })
            ->addColumn('action', function (CustomField $customField) {
                return view('pages/apps/system/custom-fields/columns/_actions', compact('customField'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @param CustomField $model
     * @return QueryBuilder
     */
    public function query(CustomField $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the HTML builder.
     *
     * @return HtmlBuilder
     */
    public function html(): HtmlBuilder
    {
        $drawScript = file_get_contents(
            resource_path('views/pages/apps/system/custom-fields/columns/_draw-scripts.js')
        );

        return $this->builder()
            ->setTableId('custom-field-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom(
                "rt" .
                    "<'row'<'col-sm-12'tr>>" .
                    "<'d-flex justify-content-between'<'col-sm-12 col-md-5'i><'d-flex justify-content-between'p>>"
            )
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(2)
            ->drawCallback("function() {{$drawScript}}");
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('label')->addClass('text-start'),
            Column::make('module'),
            Column::make('name'),
            Column::make('type'),
            Column::make('is_required')->title('Required'),
            Column::make('is_visible_in_table')->title('Visible in Table'),
            Column::make('order'),
            Column::computed('action')
                ->addClass('text-end text-nowrap')
                ->exportable(false)
                ->printable(false)
                ->width(60),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'CustomFields_' . date('YmdHis');
    }
}
