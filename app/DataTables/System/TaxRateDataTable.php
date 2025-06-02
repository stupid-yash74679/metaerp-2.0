<?php

namespace App\DataTables\System;

use App\Models\System\TaxRate;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class TaxRateDataTable extends DataTable
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
            ->rawColumns(['name', 'action', 'is_default', 'is_active', 'compound_tax', 'collective_tax'])
            ->editColumn('name', function (TaxRate $taxRate) {
                return '<a href="javascript:void(0);" '
                    . 'data-kt-tax-rate-id="' . $taxRate->id . '" '
                    . 'data-bs-toggle="modal" '
                    . 'data-bs-target="#kt_modal_add_edit_tax_rate" '
                    // data-kt-action="update_row" // This can be kept or removed if onclick is primary
                    . 'onclick="Livewire.dispatch(\'editTaxRate\', { id: ' . $taxRate->id . ' })">' // <-- ADD THIS LINE
                    . '<strong>' . e($taxRate->name) . '</strong>'
                    . '</a>';
            })
            ->editColumn('rate_percentage', function (TaxRate $taxRate) {
                return number_format($taxRate->rate_percentage, 2) . '%'; // Assuming 2 decimal places for display
            })
            ->editColumn('tax_type', function (TaxRate $taxRate) {
                return ucfirst(str_replace('_', ' ', $taxRate->tax_type));
            })
            ->editColumn('compound_tax', function (TaxRate $taxRate) {
                return $taxRate->compound_tax ? '<span class="badge badge-light-success">Yes</span>' : '<span class="badge badge-light-danger">No</span>';
            })
            ->editColumn('collective_tax', function (TaxRate $taxRate) {
                return $taxRate->collective_tax ? '<span class="badge badge-light-info">Yes</span>' : '<span class="badge badge-light-primary">No</span>';
            })
            ->editColumn('is_default', function (TaxRate $taxRate) {
                return $taxRate->is_default ? '<span class="badge badge-light-primary">Yes</span>' : '<span class="badge badge-light-secondary">No</span>';
            })
            ->editColumn('is_active', function (TaxRate $taxRate) {
                return $taxRate->is_active ? '<span class="badge badge-light-success">Active</span>' : '<span class="badge badge-light-danger">Inactive</span>';
            })
            ->editColumn('created_at', function (TaxRate $taxRate) {
                return $taxRate->created_at->format('d M Y, h:i a');
            })
            ->addColumn('action', function (TaxRate $taxRate) {
                return view('pages.apps.system.tax-rates.columns._actions', compact('taxRate'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @param TaxRate $model
     * @return QueryBuilder
     */
    public function query(TaxRate $model): QueryBuilder
    {
        return $model->newQuery()->with('creator'); // Eager load creator if displaying creator info
    }

    /**
     * Optional method if you want to use the HTML builder.
     *
     * @return HtmlBuilder
     */
    public function html(): HtmlBuilder
    {
        $drawScript = file_get_contents(
            resource_path('views/pages/apps/system/tax-rates/columns/_draw-scripts.js')
        );

        return $this->builder()
            ->setTableId('tax-rates-table') // Updated table ID
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0) // Order by ID by default
            ->drawCallback("function() {{$drawScript}}");
        // ->buttons([
        //     ['extend' => 'export', 'className' => 'btn btn-light-primary me-3'],
        //     ['extend' => 'reload', 'className' => 'btn btn-light-primary'],
        // ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('name')->addClass('text-start'),
            Column::make('rate_percentage')->title('Rate (%)'),
            Column::make('tax_type')->title('Type'),
            Column::make('compound_tax')->title('Compound'),
            Column::make('collective_tax')->title('Collective'),
            Column::make('region')->title('Region')->visible(false), // Often not shown by default
            Column::make('is_default')->title('Default'),
            Column::make('is_active')->title('Status'),
            Column::make('created_at')->title('Created At')->visible(false),
            Column::computed('action')
                ->addClass('text-end text-nowrap')
                ->exportable(false)
                ->printable(false)
                ->width(100),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'TaxRates_' . date('YmdHis');
    }
}
