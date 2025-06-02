// resources/views/pages/apps/system/tax-rates/show.blade.php
<x-default-layout>

    @section('title')
        Tax Rate Details
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('system.tax-rates.show', $taxRate) }}
    @endsection

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tax Rate: {{ $taxRate->name }}</h3>
            <div class="card-toolbar">
                <a href="{{ route('system.tax-rates.index') }}" class="btn btn-sm btn-light">Back to List</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Name</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $taxRate->name }}</span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Rate Percentage</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ number_format($taxRate->rate_percentage, 4) }}%</span>
                </div>
            </div>
             <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Tax Type</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ ucfirst(str_replace('_', ' ', $taxRate->tax_type)) }}</span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Compound Tax</label>
                <div class="col-lg-8">
                    <span class="badge badge-light-{{ $taxRate->compound_tax ? 'success' : 'danger' }}">{{ $taxRate->compound_tax ? 'Yes' : 'No' }}</span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Collective Tax</label>
                <div class="col-lg-8">
                    <span class="badge badge-light-{{ $taxRate->collective_tax ? 'info' : 'primary' }}">{{ $taxRate->collective_tax ? 'Yes' : 'No' }}</span>
                </div>
            </div>
            @if($taxRate->collective_tax && !empty($taxRate->components))
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Components</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ implode(', ', $taxRate->components) }}</span>
                </div>
            </div>
            @endif
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Region</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $taxRate->region ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Is Default</label>
                <div class="col-lg-8">
                     <span class="badge badge-light-{{ $taxRate->is_default ? 'primary' : 'secondary' }}">{{ $taxRate->is_default ? 'Yes' : 'No' }}</span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Status</label>
                <div class="col-lg-8">
                     <span class="badge badge-light-{{ $taxRate->is_active ? 'success' : 'danger' }}">{{ $taxRate->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Created By</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $taxRate->creator->name ?? 'System' }}</span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Created At</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $taxRate->created_at->format('F d, Y H:i:s') }}</span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Last Updated</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $taxRate->updated_at->format('F d, Y H:i:s') }}</span>
                </div>
            </div>
        </div>
    </div>

</x-default-layout>
