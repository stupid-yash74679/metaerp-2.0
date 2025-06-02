<x-default-layout>

    @php
        $followUps = old('follow_ups', $lead->follow_ups ?? []);
        $meetings = old('meetings', $lead->meetings ?? []);

        // Process dates for Overview tab
        $today = now()->startOfDay();
        $nextFollowUp = collect($followUps)
            ->filter(fn($fu) => !empty($fu['next_followup_date']) && \Carbon\Carbon::parse($fu['next_followup_date'])->gte($today))
            ->sortBy('next_followup_date')
            ->first();
        $nextFollowUpDate = $nextFollowUp ? \Carbon\Carbon::parse($nextFollowUp['next_followup_date'])->format('d M Y') : 'N/A';

        $lastFollowUp = collect($followUps)
            ->filter(fn($fu) => !empty($fu['next_followup_date']) && \Carbon\Carbon::parse($fu['next_followup_date'])->lt($today))
            ->sortByDesc('next_followup_date')
            ->first();
        $lastFollowUpDate = $lastFollowUp ? \Carbon\Carbon::parse($lastFollowUp['next_followup_date'])->format('d M Y') : 'N/A';

        $nextMeeting = collect($meetings)
            ->filter(fn($mt) => !empty($mt['meeting_date']) && \Carbon\Carbon::parse($mt['meeting_date'])->gte($today))
            ->sortBy('meeting_date')
            ->first();
        $nextMeetingDate = $nextMeeting ? \Carbon\Carbon::parse($nextMeeting['meeting_date'])->format('d M Y') : 'N/A';

        $lastMeeting = collect($meetings)
            ->filter(fn($mt) => !empty($mt['meeting_date']) && \Carbon\Carbon::parse($mt['meeting_date'])->lt($today))
            ->sortByDesc('meeting_date')
            ->first();
        $lastMeetingDate = $lastMeeting ? \Carbon\Carbon::parse($lastMeeting['meeting_date'])->format('d M Y') : 'N/A';
    @endphp

    @section('title')
        {{ $lead->exists ? 'Lead #' . $lead->enquiry_number : 'New Lead' }}
    @endsection

    @section('breadcrumbs')
        @if ($lead->exists)
            {{ Breadcrumbs::render('crm.leads.show', $lead) }}
        @else
            {{ Breadcrumbs::render('crm.leads.add') }}
        @endif
    @endsection

    <form method="POST" action="{{ route('leads.storeOrUpdate', $lead) }}" class="form">
        @csrf

        <div class="row">
            <div class="col-md-4">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header">
                        <h3 class="card-title">Lead Details</h3>
                    </div>
                    <div class="card-body p-10">
                        <div class="row mb-7">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" name="first_name" class="form-control" id="first_name"
                                        placeholder="First Name" value="{{ old('first_name', $lead->first_name) }}"
                                        required />
                                    <label for="first_name">First Name</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" name="last_name" class="form-control" id="last_name"
                                        placeholder="Last Name" value="{{ old('last_name', $lead->last_name) }}"
                                        required />
                                    <label for="last_name">Last Name</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="email" name="email" class="form-control" id="email"
                                        placeholder="Email" value="{{ old('email', $lead->email) }}" />
                                    <label for="email">Email</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="tel" name="phone" class="form-control" id="phone"
                                        placeholder="Phone" value="{{ old('phone', $lead->phone) }}" />
                                    <label for="phone">Phone</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" name="company" class="form-control" id="company"
                                        placeholder="Company" value="{{ old('company', $lead->company) }}" />
                                    <label for="company">Company</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <select name="status" class="form-select" id="status" required>
                                        <option value="" disabled>Select Status</option>
                                        @foreach (config('globals.statusOptions') as $statusOption)
                                            <option value="{{ $statusOption['value'] }}" @selected(old('status', $lead->status) === $statusOption['value'])>
                                                {{ $statusOption['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="status">Lead Status</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <select name="source" class="form-select" id="source">
                                        <option value="" disabled>Select Source</option>
                                        @foreach (config('globals.sourceOptions') as $sourceOption)
                                            <option value="{{ $sourceOption['value'] }}" @selected(old('source', $lead->source) === $sourceOption['value'])>
                                                {{ $sourceOption['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="source">Lead Source</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <textarea name="inquiry_about" class="form-control" id="inquiry_about" placeholder="Inquiry About" style="height: 80px">{{ old('inquiry_about', $lead->inquiry_about) }}</textarea>
                                    <label for="inquiry_about">Inquiry About</label>
                                </div>
                            </div>
                        </div>
                        @if (!empty($customFields))
                            @foreach ($customFields as $field)
                                @php
                                    // Retrieve the saved value from the custom_fields array on the lead
                                    // The structure is now: $lead->custom_fields[field_id]['value']
                                    $savedCustomFieldValue = $lead->custom_fields[$field->id]['value'] ?? null;
                                @endphp

                                @if ($field->type === 'text')
                                    <div class="row mb-7">
                                        <div class="col-md-12">
                                            <div class="form-floating">
                                                <input type="text"
                                                       name="custom_fields[{{ $field->id }}]"
                                                       class="form-control"
                                                       id="custom_field_{{ $field->id }}"
                                                       placeholder="{{ $field->label }}"
                                                       value="{{ old('custom_fields.' . $field->id, $savedCustomFieldValue) }}">
                                                <label for="custom_field_{{ $field->id }}">{{ $field->label }}</label>
                                            </div>
                                        </div>
                                    </div>
                                @elseif ($field->type === 'textarea')
                                    <div class="row mb-7">
                                        <div class="col-md-12">
                                            <div class="form-floating">
                                                <textarea name="custom_fields[{{ $field->id }}]"
                                                          class="form-control"
                                                          id="custom_field_{{ $field->id }}"
                                                          placeholder="{{ $field->label }}"
                                                          style="height: 80px">{{ old('custom_fields.' . $field->id, $savedCustomFieldValue) }}</textarea>
                                                <label for="custom_field_{{ $field->id }}">{{ $field->label }}</label>
                                            </div>
                                        </div>
                                    </div>
                                @elseif ($field->type === 'number')
                                    <div class="row mb-7">
                                        <div class="col-md-12">
                                            <div class="form-floating">
                                                <input type="number"
                                                       name="custom_fields[{{ $field->id }}]"
                                                       class="form-control"
                                                       id="custom_field_{{ $field->id }}"
                                                       placeholder="{{ $field->label }}"
                                                       value="{{ old('custom_fields.' . $field->id, $savedCustomFieldValue) }}">
                                                <label for="custom_field_{{ $field->id }}">{{ $field->label }}</label>
                                            </div>
                                        </div>
                                    </div>
                                @elseif ($field->type === 'date')
                                    <div class="row mb-7">
                                        <div class="col-md-12">
                                            <div class="form-floating">
                                                <input type="date"
                                                       name="custom_fields[{{ $field->id }}]"
                                                       class="form-control"
                                                       id="custom_field_{{ $field->id }}"
                                                       value="{{ old('custom_fields.' . $field->id, $savedCustomFieldValue) }}">
                                                <label for="custom_field_{{ $field->id }}">{{ $field->label }}</label>
                                            </div>
                                        </div>
                                    </div>
                                @elseif ($field->type === 'select')
                                    <div class="row mb-7">
                                        <div class="col-md-12">
                                            <div class="form-floating">
                                                <select name="custom_fields[{{ $field->id }}]"
                                                        class="form-select"
                                                        id="custom_field_{{ $field->id }}">
                                                    <option value="" disabled>Select {{ $field->label }}</option>
                                                    @foreach ($field->options as $option)
                                                        <option value="{{ $option }}" @selected(old('custom_fields.' . $field->id, $savedCustomFieldValue) === $option)>
                                                            {{ $option }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <label for="custom_field_{{ $field->id }}">{{ $field->label }}</label>
                                            </div>
                                        </div>
                                    </div>
                                @elseif ($field->type === 'checkbox')
                                    <div class="row mb-7">
                                        <div class="col-md-12">
                                            <div class="form-check form-check-custom form-check-solid">
                                                <input type="checkbox"
                                                       class="form-check-input"
                                                       name="custom_fields[{{ $field->id }}]"
                                                       id="custom_field_{{ $field->id }}"
                                                       value="1"
                                                       {{ (old('custom_fields.' . $field->id, $savedCustomFieldValue) == 1) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="custom_field_{{ $field->id }}">
                                                    {{ $field->label }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @elseif ($field->type === 'radio')
                                    <div class="row mb-7">
                                        <div class="col-md-12">
                                            <label class="fw-semibold fs-6 mb-2">{{ $field->label }}</label>
                                            <div class="d-flex flex-column">
                                                @foreach ($field->options as $option)
                                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                                        <input class="form-check-input" type="radio"
                                                               value="{{ $option }}"
                                                               name="custom_fields[{{ $field->id }}]"
                                                               id="custom_field_{{ $field->id }}_{{ Str::slug($option) }}"
                                                               {{ (old('custom_fields.' . $field->id, $savedCustomFieldValue) == $option) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="custom_field_{{ $field->id }}_{{ Str::slug($option) }}">
                                                            {{ $option }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                        <div class="row mb-7">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <textarea name="notes" class="form-control" id="notes" placeholder="Notes" style="height: 100px">{{ old('notes', $lead->notes) }}</textarea>
                                    <label for="notes">Notes</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-end mt-5">
                                    <a href="{{ route('leads.index') }}" class="btn btn-light me-3">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        {{ $lead->exists ? 'Update Lead' : 'Create Lead' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-0">
                        <div class="card-title m-0">
                            <h3 class="card-title fw-bold m-0">Lead Activities</h3>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6" id="leadTabs"
                            role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="overview-tab" data-bs-toggle="tab" href="#overview"
                                    role="tab" aria-controls="overview" aria-selected="true">
                                    Overview
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="followups-tab" data-bs-toggle="tab" href="#followups"
                                    role="tab" aria-controls="followups" aria-selected="false">
                                    Follow-ups
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="meetings-tab" data-bs-toggle="tab" href="#meetings"
                                    role="tab" aria-controls="meetings" aria-selected="false">
                                    Meetings
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content" id="leadTabsContent">
                            <div class="tab-pane fade show active" id="overview" role="tabpanel"
                                aria-labelledby="overview-tab">
                                <div class="card-body p-5">
                                    <h4 class="fw-bold mb-5">Lead Activity Summary</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex border border-gray-300 border-dashed rounded p-6 mb-6">
                                                <div class="d-flex align-items-center flex-grow-1 me-2">
                                                    <div class="symbol symbol-50px me-4">
                                                        <span class="symbol-label">
                                                            <i class="ki-duotone ki-timer fs-2qx text-primary">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                                <span class="path3"></span>
                                                            </i>
                                                        </span>
                                                    </div>
                                                    <div class="me-2">
                                                        <span class="text-gray-800 fs-6 fw-bold">Next Follow-up</span>
                                                        <span class="text-gray-500 fw-bold d-block fs-7">
                                                            {{ $nextFollowUpDate === 'N/A' ? 'No follow-up scheduled' : 'Scheduled follow-up date' }}
                                                        </span>
                                                    </div>
                                                    </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="text-gray-900 fw-bolder fs-2x">{{ $nextFollowUpDate }}</span>
                                                    <span class="badge badge-lg badge-light-primary align-self-center px-2 ms-3">
                                                        {{ $nextFollowUpDate === 'N/A' ? 'None' : 'Upcoming' }}
                                                    </span>
                                                </div>
                                                </div>
                                            </div>
                                        <div class="col-md-6">
                                            <div class="d-flex border border-gray-300 border-dashed rounded p-6 mb-6">
                                                <div class="d-flex align-items-center flex-grow-1 me-2">
                                                    <div class="symbol symbol-50px me-4">
                                                        <span class="symbol-label">
                                                            <i class="ki-duotone ki-calendar fs-2qx text-primary">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                        </span>
                                                    </div>
                                                    <div class="me-2">
                                                        <span class="text-gray-800 fs-6 fw-bold">Next Meeting</span>
                                                        <span class="text-gray-500 fw-bold d-block fs-7">
                                                            {{ $nextMeetingDate === 'N/A' ? 'No meeting scheduled' : 'Scheduled meeting date' }}
                                                        </span>
                                                    </div>
                                                    </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="text-gray-900 fw-bolder fs-2x">{{ $nextMeetingDate }}</span>
                                                    <span class="badge badge-lg badge-light-primary align-self-center px-2 ms-3">
                                                        {{ $nextMeetingDate === 'N/A' ? 'None' : 'Upcoming' }}
                                                    </span>
                                                </div>
                                                </div>
                                            </div>
                                        <div class="col-md-6">
                                            <div class="d-flex border border-gray-300 border-dashed rounded p-6 mb-6">
                                                <div class="d-flex align-items-center flex-grow-1 me-2">
                                                    <div class="symbol symbol-50px me-4">
                                                        <span class="symbol-label">
                                                            <i class="ki-duotone ki-timer fs-2qx text-secondary">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                                <span class="path3"></span>
                                                            </i>
                                                        </span>
                                                    </div>
                                                    <div class="me-2">
                                                        <span class="text-gray-800 fs-6 fw-bold">Last Follow-up</span>
                                                        <span class="text-gray-500 fw-bold d-block fs-7">
                                                            {{ $lastFollowUpDate === 'N/A' ? 'No previous follow-up' : 'Most recent follow-up' }}
                                                        </span>
                                                    </div>
                                                    </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="text-gray-900 fw-bolder fs-2x">{{ $lastFollowUpDate }}</span>
                                                    <span class="badge badge-lg badge-light-secondary align-self-center px-2 ms-3">
                                                        {{ $lastFollowUpDate === 'N/A' ? 'None' : 'Past' }}
                                                    </span>
                                                </div>
                                                </div>
                                            </div>
                                        <div class="col-md-6">
                                            <div class="d-flex border border-gray-300 border-dashed rounded p-6 mb-6">
                                                <div class="d-flex align-items-center flex-grow-1 me-2">
                                                    <div class="symbol symbol-50px me-4">
                                                        <span class="symbol-label">
                                                            <i class="ki-duotone ki-calendar fs-2qx text-secondary">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                        </span>
                                                    </div>
                                                    <div class="me-2">
                                                        <span class="text-gray-800 fs-6 fw-bold">Last Meeting</span>
                                                        <span class="text-gray-500 fw-bold d-block fs-7">
                                                            {{ $lastMeetingDate === 'N/A' ? 'No previous meeting' : 'Most recent meeting' }}
                                                        </span>
                                                    </div>
                                                    </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="text-gray-900 fw-bolder fs-2x">{{ $lastMeetingDate }}</span>
                                                    <span class="badge badge-lg badge-light-secondary align-self-center px-2 ms-3">
                                                        {{ $lastMeetingDate === 'N/A' ? 'None' : 'Past' }}
                                                    </span>
                                                </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="followups" role="tabpanel"
                                aria-labelledby="followups-tab">
                                <div id="kt_docs_repeater_followups">
                                    <div data-repeater-list="follow_ups">
                                        <div data-repeater-item style="display: none;">
                                            <div class="form-group row mb-5">
                                                <div class="col-md-3">
                                                    <div class="form-floating">
                                                        <input type="text" name="follow_ups[][type]"
                                                            class="form-control" placeholder="Type">
                                                        <label>Type</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-floating">
                                                        <input type="date" name="follow_ups[][next_followup_date]"
                                                            class="form-control">
                                                        <label>Next Follow-up Date</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-floating">
                                                        <input type="text" name="follow_ups[][task_by_lead]"
                                                            class="form-control" placeholder="Task by Lead">
                                                        <label>Task by Lead</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-floating">
                                                        <input type="text" name="follow_ups[][task_by_us]"
                                                            class="form-control" placeholder="Task by Us">
                                                        <label>Task by Us</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-9 mt-3">
                                                    <div class="form-floating">
                                                        <textarea name="follow_ups[][notes]" class="form-control" placeholder="Notes" style="height: 60px"></textarea>
                                                        <label>Notes</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 d-flex align-items-center mt-3">
                                                    <a href="javascript:;" data-repeater-delete
                                                        class="btn btn-sm btn-light-danger">
                                                        <i class="ki-duotone ki-trash fs-5"></i> Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @foreach ($followUps as $fu)
                                            <div data-repeater-item>
                                                <div class="form-group row mb-5">
                                                    <div class="col-md-3">
                                                        <div class="form-floating">
                                                            <input type="text" name="follow_ups[][type]"
                                                                class="form-control" placeholder="Type"
                                                                value="{{ $fu['type'] ?? '' }}">
                                                            <label>Type</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-floating">
                                                            <input type="date"
                                                                name="follow_ups[][next_followup_date]"
                                                                class="form-control"
                                                                value="{{ $fu['next_followup_date'] ?? '' }}">
                                                            <label>Next Follow-up Date</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-floating">
                                                            <input type="text" name="follow_ups[][task_by_lead]"
                                                                class="form-control" placeholder="Task by Lead"
                                                                value="{{ $fu['task_by_lead'] ?? '' }}">
                                                            <label>Task by Lead</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-floating">
                                                            <input type="text" name="follow_ups[][task_by_us]"
                                                                class="form-control" placeholder="Task by Us"
                                                                value="{{ $fu['task_by_us'] ?? '' }}">
                                                            <label>Task by Us</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-9 mt-3">
                                                        <div class="form-floating">
                                                            <textarea name="follow_ups[][notes]" class="form-control" placeholder="Notes" style="height: 60px">{{ $fu['notes'] ?? '' }}</textarea>
                                                            <label>Notes</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 d-flex align-items-center mt-3">
                                                        <a href="javascript:;" data-repeater-delete
                                                            class="btn btn-sm btn-light-danger">
                                                            <i class="ki-duotone ki-trash fs-5"></i> Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="form-group mt-5">
                                        <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                                            <i class="ki-duotone ki-plus fs-3"></i> Add Follow-up
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="meetings" role="tabpanel"
                                aria-labelledby="meetings-tab">
                                <div id="kt_docs_repeater_meetings">
                                    <div data-repeater-list="meetings">
                                        <div data-repeater-item style="display: none;">
                                            <div class="form-group row mb-5">
                                                <div class="col-md-3">
                                                    <div class="form-floating">
                                                        <input type="datetime-local" name="meetings[][meeting_date]"
                                                            class="form-control">
                                                        <label>Meeting Date</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-floating">
                                                        <input type="text" name="meetings[][subject]"
                                                            class="form-control" placeholder="Subject">
                                                        <label>Subject</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-floating">
                                                        <input type="text" name="meetings[][location]"
                                                            class="form-control" placeholder="Location">
                                                        <label>Location</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-floating">
                                                        <input type="text" name="meetings[][attendees]"
                                                            class="form-control"
                                                            placeholder="Attendees (comma-separated)">
                                                        <label>Attendees</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-9 mt-3">
                                                    <div class="form-floating">
                                                        <textarea name="meetings[][notes]" class="form-control" placeholder="Notes" style="height: 60px"></textarea>
                                                        <label>Notes</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 d-flex align-items-center mt-3">
                                                    <a href="javascript:;" data-repeater-delete
                                                        class="btn btn-sm btn-light-danger">
                                                        <i class="ki-duotone ki-trash fs-5"></i> Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @foreach ($meetings as $mt)
                                            <div data-repeater-item>
                                                <div class="form-group row mb-5">
                                                    <div class="col-md-3">
                                                        <div class="form-floating">
                                                            <input type="datetime-local"
                                                                name="meetings[][meeting_date]" class="form-control"
                                                                value="{{ $mt['meeting_date'] ?? '' }}">
                                                            <label>Meeting Date</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-floating">
                                                            <input type="text" name="meetings[][subject]"
                                                                class="form-control" placeholder="Subject"
                                                                value="{{ $mt['subject'] ?? '' }}">
                                                            <label>Subject</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-floating">
                                                            <input type="text" name="meetings[][location]"
                                                                class="form-control" placeholder="Location"
                                                                value="{{ $mt['location'] ?? '' }}">
                                                            <label>Location</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-floating">
                                                            <input type="text" name="meetings[][attendees]"
                                                                class="form-control"
                                                                placeholder="Attendees (comma-separated)"
                                                                value="{{ $mt['attendees'] ?? '' }}">
                                                            <label>Attendees</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-9 mt-3">
                                                        <div class="form-floating">
                                                            <textarea name="meetings[][notes]" class="form-control" placeholder="Notes" style="height: 60px">{{ $mt['notes'] ?? '' }}</textarea>
                                                            <label>Notes</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 d-flex align-items-center mt-3">
                                                        <a href="javascript:;" data-repeater-delete
                                                            class="btn btn-sm btn-light-danger">
                                                            <i class="ki-duotone ki-trash fs-5"></i> Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="form-group mt-5">
                                        <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                                            <i class="ki-duotone ki-plus fs-3"></i> Add Meeting
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <script src="/assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>
        <script>
            KTUtil.onDOMContentLoaded(function() {
                // Prevent duplicate repeater initialization
                if ($('#kt_docs_repeater_followups').data('repeater-initialized')) {
                    console.warn('Follow-ups repeater already initialized, skipping.');
                    return;
                }
                if ($('#kt_docs_repeater_meetings').data('repeater-initialized')) {
                    console.warn('Meetings repeater already initialized, skipping.');
                    return;
                }

                // Initialize Follow-ups repeater
                $('#kt_docs_repeater_followups').repeater({
                    initEmpty: false,
                    show: function() {
                        const $item = $(this);
                        $item.slideDown();
                    },
                    hide: function(deleteElement) {
                        $(this).slideUp(deleteElement);
                    }
                }).data('repeater-initialized', true);

                // Initialize Meetings repeater
                $('#kt_docs_repeater_meetings').repeater({
                    initEmpty: false,
                    show: function() {
                        const $item = $(this);
                        $item.slideDown();
                    },
                    hide: function(deleteElement) {
                        $(this).slideUp(deleteElement);
                    }
                }).data('repeater-initialized', true);
            });
        </script>
    @endpush
</x-default-layout>
