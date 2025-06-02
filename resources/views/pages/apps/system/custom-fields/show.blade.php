<x-default-layout>

    @section('title')
        Custom Field Details
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('system.custom-field-definitions.show', $customField) }}
    @endsection

    <div class="card">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <h2>Custom Field: {{ $customField->label }}</h2>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('system.custom-field-definitions.index') }}" class="btn btn-primary">Back to List</a>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="d-flex flex-column gap-5 gap-md-7">
                <div class="d-flex flex-column gap-5 gap-md-7">
                    <div class="fv-row">
                        <label class="fw-bold fs-6 mb-2">Module:</label>
                        <p class="text-gray-800 fs-4 fw-normal">{{ $customField->module }}</p>
                    </div>
                    <div class="fv-row">
                        <label class="fw-bold fs-6 mb-2">Label:</label>
                        <p class="text-gray-800 fs-4 fw-normal">{{ $customField->label }}</p>
                    </div>
                    <div class="fv-row">
                        <label class="fw-bold fs-6 mb-2">Name:</label>
                        <p class="text-gray-800 fs-4 fw-normal">{{ $customField->name }}</p>
                    </div>
                    <div class="fv-row">
                        <label class="fw-bold fs-6 mb-2">Type:</label>
                        <p class="text-gray-800 fs-4 fw-normal">{{ $customField->type }}</p>
                    </div>
                    <div class="fv-row">
                        <label class="fw-bold fs-6 mb-2">Options:</label>
                        <p class="text-gray-800 fs-4 fw-normal">
                            @if($customField->options)
                                <ul>
                                    @foreach($customField->options as $option)
                                        <li>{{ $option }}</li>
                                    @endforeach
                                </ul>
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div class="fv-row">
                        <label class="fw-bold fs-6 mb-2">Required:</label>
                        <p class="text-gray-800 fs-4 fw-normal">{{ $customField->is_required ? 'Yes' : 'No' }}</p>
                    </div>
                    <div class="fv-row">
                        <label class="fw-bold fs-6 mb-2">Visible in Table:</label>
                        <p class="text-gray-800 fs-4 fw-normal">{{ $customField->is_visible_in_table ? 'Yes' : 'No' }}</p>
                    </div>
                    <div class="fv-row">
                        <label class="fw-bold fs-6 mb-2">Order:</label>
                        <p class="text-gray-800 fs-4 fw-normal">{{ $customField->order }}</p>
                    </div>
                    <div class="fv-row">
                        <label class="fw-bold fs-6 mb-2">Created By:</label>
                        <p class="text-gray-800 fs-4 fw-normal">
                            @if ($customField->createdBy)
                                {{ $customField->createdBy->name }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-default-layout>
