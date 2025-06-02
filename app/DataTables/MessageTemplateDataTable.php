<?php

namespace App\DataTables; // Corrected namespace

use App\Models\MessageTemplate;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class MessageTemplateDataTable extends DataTable
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
            ->rawColumns(['name', 'action']) // name will be a link for editing
            ->editColumn('name', function (MessageTemplate $template) {
                // Link to edit modal or a show page (for now, modal)
                return '<a href="javascript:void(0);" '
                    . 'data-kt-template-id="' . $template->id . '" '
                    . 'data-bs-toggle="modal" '
                    . 'data-bs-target="#kt_modal_add_message_template" ' // Modal ID
                    . 'data-kt-action="update_row">'
                    . '<strong>' . e($template->name) . '</strong>'
                    . '</a>';
            })
            ->editColumn('channel', function (MessageTemplate $template) {
                return ucfirst($template->channel); // e.g., Email, Whatsapp
            })
            ->editColumn('subject', function (MessageTemplate $template) {
                return e($template->subject);
            })
            ->editColumn('created_at', function (MessageTemplate $template) {
                return $template->created_at->format('d M Y, H:i A');
            })
            ->addColumn('action', function (MessageTemplate $template) {
                return view('pages.apps.system.message-templates.columns._actions', compact('template'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @param MessageTemplate $model
     * @return QueryBuilder
     */
    public function query(MessageTemplate $model): QueryBuilder
    {
        return $model->newQuery()->with('creator'); // Eager load creator if you display it
    }

    /**
     * Optional method if you want to use the HTML builder.
     *
     * @return HtmlBuilder
     */
    public function html(): HtmlBuilder
    {
        $drawScript = ''; // Initialize an empty string for drawScript
        $drawScriptPath = resource_path('views/pages/apps/system/message-templates/columns/_draw-scripts.js');
        if (file_exists($drawScriptPath)) {
            $drawScript = file_get_contents($drawScriptPath);
        }

        return $this->builder()
            ->setTableId('message-template-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom(
                "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" . // l for length menu, f for filtering input
                "<'row'<'col-sm-12'tr>>" .
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" // i for info, p for pagination
            )
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0, 'asc') // Order by the first column (ID) by default
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
            Column::make('id')->title('ID')->width(50),
            Column::make('name')->title('Template Name'),
            Column::make('channel')->title('Channel'),
            Column::make('subject')->title('Subject (for Email)'),
            Column::make('created_at')->title('Created At'),
            Column::computed('action')
                ->addClass('text-end text-nowrap')
                ->exportable(false)
                ->printable(false)
                ->width(80),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'MessageTemplates_' . date('YmdHis');
    }
}
