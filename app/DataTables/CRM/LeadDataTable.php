<?php

namespace App\DataTables\CRM;

use App\Models\CRM\Lead;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Carbon\Carbon;

class LeadDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $today = Carbon::now()->startOfDay();

        $dataTable = (new EloquentDataTable($query))
            ->rawColumns(['enquiry_number', 'contact_info', 'status_source', 'next_activity'])
            ->editColumn('created_at', function (Lead $lead) {
                return $lead->created_at->format('d M Y H:i A');
            });

        $dataTable->editColumn('enquiry_number', fn(Lead $lead) => '<a href="' . route('leads.show', $lead) . '"><strong>Lead#' . e($lead->enquiry_number) . '</strong></a>')
            ->addColumn('contact_info', function (Lead $lead) {
                $html = '<strong>' . e($lead->first_name . ' ' . $lead->last_name) . '</strong>';
                if (!empty($lead->email)) {
                    $html .= '<br><small class="text-muted">' . e($lead->email) . '</small>';
                }
                if (!empty($lead->phone)) {
                    $html .= '<br><small class="text-muted">' . e($lead->phone) . '</small>';
                }
                // Moved company here
                if (!empty($lead->company)) {
                    $html .= '<br><small class="text-muted">Company: ' . e($lead->company) . '</small>';
                }
                return $html;
            })
            // Removed: addColumn('company', fn(Lead $lead) => e($lead->company))
            ->addColumn('status_source', function (Lead $lead) {
                $statusHtml = '';
                foreach (config('globals.statusOptions') as $option) {
                    if ($option['value'] === $lead->status) {
                        $statusHtml = $option['html'];
                        break;
                    }
                }

                $sourceIconHtml = '';
                $sourceLabel = e($lead->source);
                foreach (config('globals.sourceOptions') as $option) {
                    if ($option['value'] === $lead->source) {
                        $sourceIconClass = $option['icon_class'] ?? '';
                        $sourceLabel = $option['label'];
                        if (!empty($sourceIconClass)) {
                            $sourceIconHtml = '<i class="ki-duotone ki-' . $sourceIconClass . ' fs-7 me-2"></i>';
                        }
                        break;
                    }
                }

                $combinedHtml = $statusHtml;
                if (!empty($sourceLabel)) {
                    $combinedHtml .= '<br><small class="text-muted">' . $sourceIconHtml . e($sourceLabel) . '</small>';
                }
                return $combinedHtml;
            })
            ->addColumn('next_activity', function (Lead $lead) use ($today) {
                $nextFollowUp = collect($lead->follow_ups)
                    ->filter(fn($fu) => !empty($fu['next_followup_date']) && Carbon::parse($fu['next_followup_date'])->gte($today))
                    ->sortBy('next_followup_date')
                    ->first();

                $nextMeeting = collect($lead->meetings)
                    ->filter(fn($mt) => !empty($mt['meeting_date']) && Carbon::parse($mt['meeting_date'])->gte($today))
                    ->sortBy('meeting_date')
                    ->first();

                $nextActivityHtml = '<span class="text-muted">No Upcoming Activity</span>';

                if ($nextFollowUp || $nextMeeting) {
                    $earliestActivity = null;
                    $earliestType = '';
                    $earliestDetails = '';

                    if ($nextFollowUp && $nextMeeting) {
                        $followUpDate = Carbon::parse($nextFollowUp['next_followup_date']);
                        $meetingDate = Carbon::parse($nextMeeting['meeting_date']);

                        if ($followUpDate->lte($meetingDate)) {
                            $earliestActivity = $nextFollowUp;
                            $earliestType = 'Follow-up';
                            $earliestDetails = e($nextFollowUp['task_by_us'] ?? $nextFollowUp['notes'] ?? '');
                        } else {
                            $earliestActivity = $nextMeeting;
                            $earliestType = 'Meeting';
                            $earliestDetails = e($nextMeeting['subject'] ?? $nextMeeting['notes'] ?? '');
                        }
                    } elseif ($nextFollowUp) {
                        $earliestActivity = $nextFollowUp;
                        $earliestType = 'Follow-up';
                        $earliestDetails = e($nextFollowUp['task_by_us'] ?? $nextFollowUp['notes'] ?? '');
                    } elseif ($nextMeeting) {
                        $earliestActivity = $nextMeeting;
                        $earliestType = 'Meeting';
                        $earliestDetails = e($nextMeeting['subject'] ?? $nextMeeting['notes'] ?? '');
                    }

                    if ($earliestActivity) {
                        $activityDate = Carbon::parse($earliestActivity['next_followup_date'] ?? $earliestActivity['meeting_date']);
                        $nextActivityHtml = '<strong>' . e($earliestType) . '</strong> on ' . $activityDate->format('d M Y');
                        if (!empty($earliestDetails)) {
                            $nextActivityHtml .= '<br><small class="text-muted">' . $earliestDetails . '</small>';
                        }
                    }
                }
                return $nextActivityHtml;
            });

        return $dataTable->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @param Lead $model
     * @return QueryBuilder
     */
    public function query(Lead $model): QueryBuilder
    {
        $query = $model->newQuery()->with('owner');

        // Apply Status filter if present in the request
        if ($this->request()->has('status_filter') && $this->request()->get('status_filter') != '') {
            $query->where('status', $this->request()->get('status_filter'));
        }

        // Apply Source filter if present in the request
        if ($this->request()->has('source_filter') && $this->request()->get('source_filter') != '') {
            $query->where('source', $this->request()->get('source_filter'));
        }

        // Apply Date Range filter if present in the request
        if ($this->request()->has('start_date') && $this->request()->get('start_date') != '' &&
            $this->request()->has('end_date') && $this->request()->get('end_date') != '') {

            $startDate = Carbon::parse($this->request()->get('start_date'))->startOfDay();
            $endDate = Carbon::parse($this->request()->get('end_date'))->endOfDay();

            // Filter leads based on their creation date within the range
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the HTML builder.
     *
     * @return HtmlBuilder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('lead-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("rt" .
                "<'row'<'col-sm-12'tr>>" .
                "<'d-flex justify-content-between'<'col-sm-12 col-md-5'i><'d-flex justify-content-between'l><'col-sm-12 col-md-7'p>>")
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0)
            ->parameters([
                'language' => [
                    'zeroRecords' => 'No matching leads found. Do you want to create a <a href="' . route('leads.add') . '">New lead</a>?',
                    'emptyTable'  => 'No Leads Found. Do you want to create a <a href="' . route('leads.add') . '">New lead</a>?',
                ],
                'lengthMenu' => [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'All']
                ],
                'colReorder' => true,
                'stateSave'  => true,
            ])
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/crm/leads/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        $columns = [
            Column::make('enquiry_number')->title('Lead#')->width('100px'),
            Column::computed('contact_info')->title('Contact Info')->orderable(false)->searchable(false)->width('250px'), // Increased width as company is merged
            // Removed: Column::make('company')->width('150px'),
            Column::computed('status_source')->title('Status & Source')->orderable(false)->searchable(false)->width('180px'),
            Column::computed('next_activity')->title('Next Activity')->orderable(false)->searchable(false)->width('180px'),
            Column::make('created_at')->title('Created At')->width('150px'),
        ];

        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Leads_' . date('YmdHis');
    }
}
