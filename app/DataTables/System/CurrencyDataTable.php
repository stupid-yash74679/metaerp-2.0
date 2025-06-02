<?php

namespace App\DataTables\System;

use App\Models\Currency;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class CurrencyDataTable extends DataTable
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
            ->rawColumns(['code'])
            ->editColumn('code', function (Currency $currency) {
                return '<a href="javascript:void(0);" '
                    . 'data-kt-currency-id="' . $currency->id . '" '
                    . 'data-bs-toggle="modal" '
                    . 'data-bs-target="#kt_modal_add_currency" '
                    . 'data-kt-action="update_row">'
                    . '<strong>' . e($currency->code) . '</strong>'
                    . '</a>';
            })
            ->editColumn('name', function (Currency $currency) {
                return e($currency->name);
            })
            ->editColumn('symbol', function (Currency $currency) {
                return e($currency->symbol);
            })
            ->editColumn('exchange_rate', function (Currency $currency) {
                return number_format($currency->exchange_rate, 4);
            })
            ->addColumn('action', function (Currency $currency) {
                return view('pages/apps/system/currency/columns/_actions', compact('currency'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @param Currency $model
     * @return QueryBuilder
     */
    public function query(Currency $model): QueryBuilder
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
            resource_path('views/pages/apps/system/currency/columns/_draw-scripts.js')
        );

        return $this->builder()
            ->setTableId('currency-table')
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
            Column::make('code')->addClass('text-start'),
            Column::make('name'),
            Column::make('symbol'),
            Column::make('exchange_rate'),
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
        return 'Currencies_' . date('YmdHis');
    }
}
