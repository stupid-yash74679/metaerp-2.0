<?php

namespace App\DataTables\Contacts;

use App\Models\ContactGroup;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ContactGroupDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['name', 'description', 'created_at'])
            ->editColumn('name', function (ContactGroup $group) {
                $label = $group->is_default ? ' <span class="badge badge-light-primary fw-bold ms-2">Default</span>' : '';
                return '<a href="javascript:void(0);" '
                    . 'data-kt-contact-group-id="' . $group->id . '" '
                    . 'data-bs-toggle="modal" '
                    . 'data-bs-target="#kt_modal_add_contact_group" '
                    . 'data-kt-action="update_row">'
                    . '<strong>' . e($group->name) . '</strong>' . $label
                    . '</a>';
            })
            ->editColumn('description', function (ContactGroup $group) {
                return e($group->description);
            })
            ->addColumn('action', function (ContactGroup $group) {
                return view('pages/apps.contacts.contact-groups.columns._actions', compact('group'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ContactGroup $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('contact-group-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12'tr>><'d-flex justify-content-between'<'col-sm-12 col-md-5'i><'d-flex justify-content-between'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(2)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/contacts/contact-groups/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('name')->addClass('d-flex align-items-center'),
            Column::make('description'),
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
        return 'ContactGroups_' . date('YmdHis');
    }
}
