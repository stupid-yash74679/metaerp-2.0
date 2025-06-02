<?php

namespace App\DataTables\Projects;

use App\Models\Projects\ProjectType;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log; // For debugging

class ProjectTypeDataTable extends DataTable
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
            ->rawColumns(['name', 'is_active', 'action'])
            ->editColumn('name', function (ProjectType $projectType) {
                return '<a href="javascript:void(0);" '
                    . 'data-kt-project-type-id="' . $projectType->id . '" '
                    . 'data-bs-toggle="modal" '
                    . 'data-bs-target="#kt_modal_add_edit_project_type" '
                    . 'data-kt-action="update_row">'
                    . '<strong>' . e($projectType->name) . '</strong>'
                    . '</a>';
            })
            ->editColumn('description', function (ProjectType $projectType) {
                return e(Str::limit($projectType->description, 70));
            })
            ->addColumn('stages_count', function (ProjectType $projectType) {
                $stages = $projectType->stages; // Access the attribute (should be cast to array)

                if (is_array($stages)) {
                    return count($stages);
                } elseif (is_string($stages)) {
                    // This case indicates the model cast might not be working as expected
                    // or the data in DB is not a valid JSON array representation for casting.
                    Log::warning("ProjectType ID {$projectType->id}: 'stages' attribute is a string, not an array. Attempting to decode.", ['stages_string' => $stages]);
                    $decodedStages = json_decode($stages, true);
                    if (is_array($decodedStages)) {
                        return count($decodedStages);
                    }
                    Log::error("ProjectType ID {$projectType->id}: Failed to decode 'stages' string as JSON array.", ['stages_string' => $stages]);
                    return 0; // Or some error indicator
                } elseif (is_null($stages)) {
                    return 0; // No stages
                }
                // Fallback if it's some other unexpected type
                Log::warning("ProjectType ID {$projectType->id}: 'stages' attribute is of unexpected type.", ['type' => gettype($stages)]);
                return 0;
            })
            ->editColumn('is_active', function (ProjectType $projectType) {
                return $projectType->is_active
                    ? '<span class="badge badge-light-success">Active</span>'
                    : '<span class="badge badge-light-danger">Inactive</span>';
            })
            ->addColumn('action', function (ProjectType $projectType) {
                return view('pages.apps.projects.project-types.columns._actions', compact('projectType'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @param ProjectType $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ProjectType $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the HTML builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        $drawScript = '';
        $drawScriptPath = resource_path('views/pages/apps/projects/project-types/columns/_draw-scripts.js');
        if (file_exists($drawScriptPath)) {
            $drawScript = file_get_contents($drawScriptPath);
        }

        return $this->builder()
            ->setTableId('project_types_table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(1, 'asc')
            ->drawCallback("function() { {$drawScript} }");
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('name')->title('Name'),
            Column::make('description')->title('Description'),
            Column::computed('stages_count')->title('Stages')->orderable(false)->searchable(false)->width(80)->addClass('text-center'),
            Column::make('is_active')->title('Status')->width(100),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-end'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'ProjectTypes_' . date('YmdHis');
    }
}
