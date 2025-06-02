<?php

namespace App\DataTables\Contacts;

use App\Models\Contact;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ContactDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['name', 'email', 'phone', 'group'])
            ->editColumn('name', fn(Contact $contact) => '<a href="' . route('contacts.contacts.show', $contact) . '"><strong>' . e($contact->name) . '</strong></a>')
            ->editColumn('email', fn(Contact $contact) => e($contact->email))
            ->editColumn('phone', fn(Contact $contact) => e($contact->phone))
            ->editColumn('group', fn(Contact $contact) => e($contact->group->name))
            ->addColumn('action', fn(Contact $contact) => view('pages/apps.contacts.contacts.columns._actions', compact('contact')))
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Contact $model): QueryBuilder
    {
        return $model->newQuery()->with('group');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('contact-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12'tr>><'d-flex justify-content-between'<'col-sm-12 col-md-5'i><'d-flex justify-content-between'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(1)
            ->parameters([
                'language' => [
                    'zeroRecords' => 'No matching contacts found. Do you want to create a <a href="' . route('contacts.add') . '">New contact</a>?',
                    'emptyTable' => 'No Contacts Found. Do you want to create a <a href="' . route('contacts.add') . '">New contact</a>?',
                ],
            ])
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/contacts/contacts/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('name')->addClass('d-flex align-items-center'),
            Column::make('group')->title('Group')->searchable(false),
            Column::make('company_name')->title('Company'),
            Column::make('email'),
            Column::make('phone'),
            Column::computed('action')
                ->addClass('text-end text-nowrap')
                ->exportable(false)
                ->printable(false)
                ->width(60),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Contacts_' . date('YmdHis');
    }
}
