<x-default-layout>

    @section('title')
        Leads
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('crm.leads.index') }}
    @endsection

    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text"
                           data-kt-lead-table-filter="search"
                           class="form-control form-control-solid w-250px ps-13"
                           placeholder="Search leads"
                           id="leadSearchInput"/>
                </div>
                </div>

            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-lead-table-toolbar="base">
                    <div class="me-3 w-175px">
                        <select class="form-select form-select-solid" data-kt-select2="true" data-placeholder="Filter by Status" data-allow-clear="true" id="filterStatus">
                            <option></option>
                            @foreach (config('globals.statusOptions') as $option)
                                <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="me-3 w-175px">
                        <select class="form-select form-select-solid" data-kt-select2="true" data-placeholder="Filter by Source" data-allow-clear="true" id="filterSource">
                            <option></option>
                            @foreach (config('globals.sourceOptions') as $option)
                                <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="me-3 w-250px">
                        <input class="form-control form-control-solid" placeholder="Pick date range" id="kt_daterangepicker_1"/>
                    </div>
                    <a href="{{ route('leads.add') }}" class="btn btn-primary">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        Add Lead
                    </a>
                    </div>
                </div>
        </div>
        <div class="card-body py-4">
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            $(document).ready(function() {
                var table = window.LaravelDataTables['lead-table'];

                // Handle search input
                document.getElementById('leadSearchInput').addEventListener('keyup', function () {
                    table.search(this.value).draw();
                });

                // Initialize Select2 for filters
                $('#filterStatus').select2({
                    minimumResultsForSearch: Infinity,
                });
                $('#filterSource').select2({
                    minimumResultsForSearch: Infinity,
                });

                // Function to apply filters to DataTable
                function applyFilters() {
                    var ajaxUrl = table.ajax.url().split('?')[0];
                    var params = new URLSearchParams(table.ajax.params());

                    // Status filter
                    var status = $('#filterStatus').val();
                    if (status) {
                        params.set('status_filter', status);
                    } else {
                        params.delete('status_filter');
                    }

                    // Source filter
                    var source = $('#filterSource').val();
                    if (source) {
                        params.set('source_filter', source);
                    } else {
                        params.delete('source_filter');
                    }

                    // Date range filter
                    var dateRange = $('#kt_daterangepicker_1').val();
                    if (dateRange) {
                        var dates = dateRange.split(' - ');
                        if (dates.length === 2) {
                            params.set('start_date', dates[0]);
                            params.set('end_date', dates[1]);
                        } else {
                            params.delete('start_date');
                            params.delete('end_date');
                        }
                    } else {
                        params.delete('start_date');
                        params.delete('end_date');
                    }

                    table.ajax.url(ajaxUrl + '?' + params.toString()).load();
                }


                // Handle filter changes
                $('#filterStatus').on('change', applyFilters);
                $('#filterSource').on('change', applyFilters);

                // Initialize Daterangepicker
                var dateRangePicker = $("#kt_daterangepicker_1").daterangepicker({
                    autoUpdateInput: false, // Prevents auto-population on load
                    locale: {
                        format: 'YYYY-MM-DD',
                        cancelLabel: 'Clear' // Text for the clear button
                    },
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                        'All Time': [moment().subtract(100, 'years'), moment()], // Represents "All" leads effectively
                    }
                });

                // Update input and apply filter on date change
                dateRangePicker.on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                    applyFilters();
                });

                // Clear input and apply filter on cancel
                dateRangePicker.on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val(''); // Clear the input field
                    applyFilters();
                });

                // Optional: Set initial filter values from URL on page load
                var currentUrlParams = new URLSearchParams(window.location.search);
                var initialStatus = currentUrlParams.get('status_filter');
                var initialSource = currentUrlParams.get('source_filter');
                var initialStartDate = currentUrlParams.get('start_date');
                var initialEndDate = currentUrlParams.get('end_date');

                if (initialStatus) {
                    $('#filterStatus').val(initialStatus).trigger('change.select2');
                }
                if (initialSource) {
                    $('#filterSource').val(initialSource).trigger('change.select2');
                }
                // Pre-fill date range input if dates are in URL
                if (initialStartDate && initialEndDate) {
                    $('#kt_daterangepicker_1').val(initialStartDate + ' - ' + initialEndDate);
                }

                // Re-initialize Metronic components on DataTable draw
                table.on('draw.dt', function() {
                    KTMenu.init();
                });
            });
        </script>
    @endpush

</x-default-layout>
