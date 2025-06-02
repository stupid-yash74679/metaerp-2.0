<x-default-layout>

    @section('title')
        Company Settings
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('system.company-settings.edit') }}
    @endsection

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Manage Company Information</h3>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('system.company-settings.update') }}" enctype="multipart/form-data" class="form">
                @csrf
                @method('PUT')

                <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#kt_company_settings_general" aria-selected="true" role="tab">General</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_company_settings_address" aria-selected="false" role="tab" tabindex="-1">Address</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_company_settings_tax" aria-selected="false" role="tab" tabindex="-1">Tax & Legal</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_company_settings_social" aria-selected="false" role="tab" tabindex="-1">Social Links</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_company_settings_localization" aria-selected="false" role="tab" tabindex="-1">Localization</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_company_settings_other" aria-selected="false" role="tab" tabindex="-1">Other</a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    {{-- General Tab --}}
                    <div class="tab-pane fade active show" id="kt_company_settings_general" role="tabpanel">
                        <h4 class="mb-7">Basic Information</h4>
                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label class="required form-label">Company Name</label>
                                <input type="text" name="name" class="form-control form-control-solid @error('name') is-invalid @enderror" placeholder="Enter company name" value="{{ old('name', $companySettings->name) }}" required/>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="form-label">Display Name (Optional)</label>
                                <input type="text" name="display_name" class="form-control form-control-solid @error('display_name') is-invalid @enderror" placeholder="Short display name" value="{{ old('display_name', $companySettings->display_name) }}"/>
                                @error('display_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label class="form-label">Tagline</label>
                                <input type="text" name="tagline" class="form-control form-control-solid @error('tagline') is-invalid @enderror" placeholder="Company tagline" value="{{ old('tagline', $companySettings->tagline) }}"/>
                                @error('tagline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="form-label">Website URL</label>
                                <input type="url" name="website_url" class="form-control form-control-solid @error('website_url') is-invalid @enderror" placeholder="https://example.com" value="{{ old('website_url', $companySettings->website_url) }}"/>
                                @error('website_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label class="form-label">Company Logo</label>
                                <input type="file" name="logo" class="form-control form-control-solid @error('logo') is-invalid @enderror"/>
                                @if($companySettings->logo_path)
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($companySettings->logo_path) }}" alt="Current Logo" style="max-height: 70px; border: 1px solid #eee; padding: 5px;">
                                        <small class="d-block text-muted">Current Logo</small>
                                    </div>
                                @endif
                                @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div class="form-text">Allowed file types: png, jpg, jpeg, gif, svg. Max size: 2MB. Recommended dimensions: 300x100 pixels.</div>
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="form-label">Favicon</label>
                                <input type="file" name="favicon" class="form-control form-control-solid @error('favicon') is-invalid @enderror"/>
                                @if($companySettings->favicon_path)
                                     <div class="mt-2">
                                        <img src="{{ Storage::url($companySettings->favicon_path) }}" alt="Current Favicon" style="max-height: 32px; border: 1px solid #eee; padding: 2px;">
                                         <small class="d-block text-muted">Current Favicon</small>
                                     </div>
                                @endif
                                @error('favicon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div class="form-text">Allowed file types: png, ico. Max size: 512KB. Recommended dimensions: 32x32 or 64x64 pixels.</div>
                            </div>
                        </div>

                        <h4 class="mt-10 mb-7">Contact Details</h4>
                         <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label class="form-label">Primary Phone</label>
                                <input type="tel" name="primary_phone" class="form-control form-control-solid @error('primary_phone') is-invalid @enderror" placeholder="Enter primary phone" value="{{ old('primary_phone', $companySettings->primary_phone) }}"/>
                                @error('primary_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                             <div class="col-md-6 fv-row">
                                 <label class="form-label">General Email</label>
                                 <input type="email" name="general_email" class="form-control form-control-solid @error('general_email') is-invalid @enderror" placeholder="info@example.com" value="{{ old('general_email', $companySettings->general_email) }}"/>
                                 @error('general_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                        </div>
                         <div class="row mb-5">
                             <div class="col-md-6 fv-row">
                                 <label class="form-label">Support Email</label>
                                 <input type="email" name="support_email" class="form-control form-control-solid @error('support_email') is-invalid @enderror" placeholder="support@example.com" value="{{ old('support_email', $companySettings->support_email) }}"/>
                                 @error('support_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                             <div class="col-md-6 fv-row">
                                 <label class="form-label">HR Email</label>
                                 <input type="email" name="hr_email" class="form-control form-control-solid @error('hr_email') is-invalid @enderror" placeholder="hr@example.com" value="{{ old('hr_email', $companySettings->hr_email) }}"/>
                                 @error('hr_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                         </div>
                    </div>

                    {{-- Address Tab --}}
                    <div class="tab-pane fade" id="kt_company_settings_address" role="tabpanel">
                        <h4 class="mb-7">Registered Address</h4>
                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label class="form-label">Address Line 1</label>
                                <input type="text" name="registered_address_line1" class="form-control form-control-solid" value="{{ old('registered_address_line1', $companySettings->registered_address_line1) }}">
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="form-label">Address Line 2</label>
                                <input type="text" name="registered_address_line2" class="form-control form-control-solid" value="{{ old('registered_address_line2', $companySettings->registered_address_line2) }}">
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-md-4 fv-row">
                                <label class="form-label">City</label>
                                <input type="text" name="registered_city" class="form-control form-control-solid" value="{{ old('registered_city', $companySettings->registered_city) }}">
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class="form-label">State/Province</label>
                                <input type="text" name="registered_state_province" class="form-control form-control-solid" value="{{ old('registered_state_province', $companySettings->registered_state_province) }}">
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class="form-label">Postal Code</label>
                                <input type="text" name="registered_postal_code" class="form-control form-control-solid" value="{{ old('registered_postal_code', $companySettings->registered_postal_code) }}">
                            </div>
                        </div>
                        <div class="row mb-5">
                             <div class="col-md-6 fv-row">
                                <label class="form-label">Country</label>
                                <input type="text" name="registered_country" class="form-control form-control-solid" value="{{ old('registered_country', $companySettings->registered_country) }}">
                            </div>
                        </div>

                        <h4 class="mt-10 mb-7">Operating Address (if different)</h4>
                         {{-- Similar fields for operating address, e.g., operating_address_line1 etc. --}}
                         <p class="text-muted">Fill this if your operating address is different from the registered address.</p>
                    </div>

                    {{-- Tax & Legal Tab --}}
                    <div class="tab-pane fade" id="kt_company_settings_tax" role="tabpanel">
                        <h4 class="mb-7">Tax & Legal Identifiers (India)</h4>
                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label class="form-label">PAN Number</label>
                                <input type="text" name="pan_number" class="form-control form-control-solid @error('pan_number') is-invalid @enderror" value="{{ old('pan_number', $companySettings->pan_number) }}" placeholder="ABCDE1234F">
                                @error('pan_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="form-label">TAN Number</label>
                                <input type="text" name="tan_number" class="form-control form-control-solid @error('tan_number') is-invalid @enderror" value="{{ old('tan_number', $companySettings->tan_number) }}" placeholder="DELC12345G">
                                @error('tan_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                         <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label class="form-label">GSTIN Number</label>
                                <input type="text" name="gstin_number" class="form-control form-control-solid @error('gstin_number') is-invalid @enderror" value="{{ old('gstin_number', $companySettings->gstin_number) }}" placeholder="15-digit GSTIN">
                                @error('gstin_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                             <div class="col-md-6 fv-row">
                                 <label class="form-label">CIN Number (if applicable)</label>
                                 <input type="text" name="cin_number" class="form-control form-control-solid @error('cin_number') is-invalid @enderror" value="{{ old('cin_number', $companySettings->cin_number) }}">
                                 @error('cin_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                         </div>
                        <h4 class="mt-10 mb-7">MSME Details</h4>
                        <div class="row mb-5">
                            <div class="col-md-4 fv-row">
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="msme_registered" value="1" {{ old('msme_registered', $companySettings->msme_registered) ? 'checked' : '' }}/>
                                    <span class="form-check-label fw-semibold">Registered as MSME?</span>
                                </label>
                            </div>
                            <div class="col-md-8 fv-row">
                                <label class="form-label">Udyam Registration Number (URN)</label>
                                <input type="text" name="udyam_registration_number" class="form-control form-control-solid @error('udyam_registration_number') is-invalid @enderror" value="{{ old('udyam_registration_number', $companySettings->udyam_registration_number) }}" placeholder="UDYAM-XX-XX-XXXXXXX">
                                @error('udyam_registration_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Social Links Tab --}}
                    <div class="tab-pane fade" id="kt_company_settings_social" role="tabpanel">
                        <h4 class="mb-7">Social Media Links</h4>
                        @php
                            $socialPlatforms = ['facebook' => 'Facebook URL', 'twitter' => 'Twitter URL', 'linkedin' => 'LinkedIn URL', 'instagram' => 'Instagram URL', 'youtube' => 'YouTube URL'];
                        @endphp
                        @foreach($socialPlatforms as $key => $label)
                        <div class="fv-row mb-5">
                            <label class="form-label">{{ $label }}</label>
                            <input type="url" name="social_links[{{ $key }}]" class="form-control form-control-solid @error('social_links.'.$key) is-invalid @enderror" placeholder="Enter full URL" value="{{ old('social_links.'.$key, $companySettings->getSocialLink($key)) }}">
                            @error('social_links.'.$key) <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        @endforeach
                    </div>

                    {{-- Localization Tab --}}
                     <div class="tab-pane fade" id="kt_company_settings_localization" role="tabpanel">
                        <h4 class="mb-7">Localization & Defaults</h4>
                         <div class="row mb-5">
                             <div class="col-md-6 fv-row">
                                 <label class="form-label">Default Currency</label>
                                 <select name="default_currency_code" class="form-select form-select-solid @error('default_currency_code') is-invalid @enderror" data-control="select2" data-placeholder="Select currency">
                                     <option></option>
                                     @foreach($currencies as $currency) {{-- Passed from controller --}}
                                         <option value="{{ $currency->code }}" {{ old('default_currency_code', $companySettings->default_currency_code) == $currency->code ? 'selected' : '' }}>
                                             {{ $currency->name }} ({{ $currency->code }})
                                         </option>
                                     @endforeach
                                 </select>
                                 @error('default_currency_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                             <div class="col-md-6 fv-row">
                                 <label class="form-label">Timezone</label>
                                 {{-- For a better UX, use a dropdown of timezones. For simplicity, using text input for now. --}}
                                 <input type="text" name="timezone" class="form-control form-control-solid @error('timezone') is-invalid @enderror" value="{{ old('timezone', $companySettings->timezone) }}" placeholder="e.g., Asia/Kolkata">
                                 @error('timezone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                         </div>
                         <div class="row mb-5">
                             <div class="col-md-4 fv-row">
                                <label class="form-label">Financial Year Starts (Month)</label>
                                <select name="financial_year_start_month" class="form-select form-select-solid @error('financial_year_start_month') is-invalid @enderror">
                                    @for ($m=1; $m<=12; $m++)
                                        <option value="{{ $m }}" {{ old('financial_year_start_month', $companySettings->financial_year_start_month) == $m ? 'selected' : '' }}>
                                            {{ date('F', mktime(0,0,0,$m, 1, date('Y'))) }}
                                        </option>
                                    @endfor
                                </select>
                                 @error('financial_year_start_month') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                             <div class="col-md-4 fv-row">
                                 <label class="form-label">Date Format</label>
                                 <input type="text" name="date_format" class="form-control form-control-solid @error('date_format') is-invalid @enderror" value="{{ old('date_format', $companySettings->date_format) }}" placeholder="e.g., d-m-Y">
                                 @error('date_format') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                             <div class="col-md-4 fv-row">
                                 <label class="form-label">Time Format</label>
                                 <input type="text" name="time_format" class="form-control form-control-solid @error('time_format') is-invalid @enderror" value="{{ old('time_format', $companySettings->time_format) }}" placeholder="e.g., h:i A">
                                 @error('time_format') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                         </div>
                    </div>

                    {{-- Other Details Tab --}}
                    <div class="tab-pane fade" id="kt_company_settings_other" role="tabpanel">
                        {{-- Add fields for number_of_employees, industry_type, etc. --}}
                        <p>Other company details can be added here.</p>
                    </div>

                </div>

                <div class="text-center pt-15">
                    <button type="reset" class="btn btn-light me-3">Discard</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">Save Settings</span>
                        <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
                </form>
        </div>
    </div>

</x-default-layout>
