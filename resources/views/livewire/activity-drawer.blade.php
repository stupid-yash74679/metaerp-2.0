<!--begin::Activities drawer-->
<div id="kt_activities" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="activities" data-kt-drawer-activate="true"
    data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'lg': '300px'}" data-kt-drawer-direction="end"
    data-kt-drawer-toggle="#kt_activities_toggle" data-kt-drawer-close="#kt_activities_close">
    <div class="card shadow-none border-0 rounded-0">
        <!--begin::Header-->
        <div class="card-header" id="kt_activities_header">
            <h3 class="card-title fw-bold text-gray-900">Activity Logs</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5"
                    id="kt_activities_close">{!! getIcon('cross', 'fs-1') !!}</button>
            </div>
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="card-body position-relative" id="kt_activities_body">
            <!--begin::Content-->
            <div id="kt_activities_scroll" class="position-relative scroll-y me-n5 pe-5" data-kt-scroll="true"
                data-kt-scroll-height="auto" data-kt-scroll-wrappers="#kt_activities_body"
                data-kt-scroll-dependencies="#kt_activities_header, #kt_activities_footer" data-kt-scroll-offset="5px">
                <!--begin::Timeline items-->
                <div class="timeline">
                    @foreach ($activities as $log)
                        <div class="timeline-item">
                            <div class="timeline-line w-40px"></div>
                            <div class="timeline-icon symbol symbol-circle symbol-40px">
                                <div class="symbol-label bg-light">{!! getIcon('message-text-2', 'fs-2 text-gray-500') !!}</div>
                            </div>
                            <div class="timeline-content mb-10 mt-n1">
                                <div class="pe-3 mb-5">
                                    <div class="fs-5 fw-semibold mb-2">
                                        {{ $log->action }}
                                        <span class="badge badge-light-info text-uppercase">{{ $log->type }}</span>
                                    </div>
                                    <div class="d-flex align-items-center mt-1 fs-6">
                                        <div class="text-muted me-2 fs-7">
                                            {{ $log->created_at->diffForHumans() }} by
                                            <div class="symbol symbol-circle symbol-25px" title="{{ $log->user?->name }}">
                                                <img src="{{ $log->user?->profile_photo_url ?? image('avatars/blank.png') }}" alt="{{ $log->user?->name }}" />
                                            </div> {{ $log->user?->name }}.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!--end::Timeline items-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Body-->
        <!--begin::Footer-->
        <div class="card-footer py-5 text-center" id="kt_activities_footer">
            <a href="#" class="btn btn-bg-body text-primary">View All Activities {!! getIcon('arrow-right', 'fs-3 text-primary') !!}</a>
        </div>
        <!--end::Footer-->
    </div>
</div>
<!--end::Activities drawer-->
