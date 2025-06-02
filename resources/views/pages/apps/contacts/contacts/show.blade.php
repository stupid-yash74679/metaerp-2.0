<x-default-layout>
    @php
        $contactPersons = old('contact_persons', $contact->contact_persons ?? []);
        $addresses = old('addresses', $contact->addresses ?? []);
    @endphp
    @section('title')
        {{ $contact->exists ? $contact->name : 'New Contact' }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('contacts.contacts.show', $contact) }}
    @endsection

    <div class="card">
        <form method="POST" action="{{ route('contacts.storeOrUpdate', $contact) }}" class="form p-10">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <h1 class="anchor fw-bold">Contact Details</h1>
                    <div class="separator border-2 my-10"></div>
                </div>
            </div>
            <!--begin::Row-->
            <div class="row mb-7">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" name="name" class="form-control" id="name" placeholder="Name"
                            value="{{ old('name', $contact->name) }}" required />
                        <label for="name">Name</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check form-switch form-check-custom form-check-solid mt-3">
                        <input class="form-check-input" type="checkbox" name="is_customer" value="1"
                            @checked(old('is_customer', $contact->is_customer)) />
                        <label class="form-check-label">Is Customer</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check form-switch form-check-custom form-check-solid mt-3">
                        <input class="form-check-input" type="checkbox" name="is_vendor" value="1"
                            @checked(old('is_vendor', $contact->is_vendor)) />
                        <label class="form-check-label">Is Vendor</label>
                    </div>
                </div>
            </div>
            <div class="row mb-7">
                <div class="col-md-6">
                    <div class="form-floating">
                        <select name="contact_group_id" data-control="select2" data-placeholder="Select Contact Group"
                            class="form-select" id="contact_group_id" required>
                            <option></option>
                            @foreach ($contactGroups as $group)
                                <option value="{{ $group->id }}" @selected(old('contact_group_id', $contact->contact_group_id) == $group->id)>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                        <label for="contact_group_id">Contact Group</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <select name="contact_type" class="form-select" data-control="select2"
                            data-placeholder="Select Contact Group" id="contact_type" required>
                            <option></option>
                            <option value="individual" @selected(old('contact_type', $contact->contact_type) == 'individual')>Individual</option>
                            <option value="company" @selected(old('contact_type', $contact->contact_type) == 'company')>Company</option>
                        </select>
                        <label for="contact_type">Contact Type</label>
                    </div>
                </div>
            </div>
            <!--begin::Row-->
            <div class="row mb-7">
                <div class="col-md-6">
                    <div class="form-floating">
                        <select name="lead_id" class="form-select" id="lead_id" data-control="select2" data-placeholder="Select Lead">
                            <option></option>
                            @foreach($leads as $lead)
                                <option value="{{ $lead->id }}" @selected(old('lead_id', $contact->lead_id) == $lead->id)>
                                    Lead#{{ $lead->enquiry_number }} — {{ $lead->first_name }} {{ $lead->last_name }}
                                </option>
                            @endforeach
                        </select>
                        <label for="lead_id">Associated Lead</label>
                    </div>
                </div>
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row mb-7">
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="text" name="company_name" class="form-control" id="company_name"
                            placeholder="Company Name" value="{{ old('company_name', $contact->company_name) }}" />
                        <label for="company_name">Company Name</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="email" name="email" class="form-control" id="email" placeholder="Email"
                            value="{{ old('email', $contact->email) }}" required />
                        <label for="email">Email</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone"
                            value="{{ old('phone', $contact->phone) }}" required />
                        <label for="phone">Phone</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="text" name="website" class="form-control" id="website" placeholder="Website"
                            value="{{ old('website', $contact->website) }}" />
                        <label for="website">Website</label>
                    </div>
                </div>
            </div>
            <div class="row mb-7">
                <div class="col-md-12">
                    <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_other_details">Other
                                Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_contact_addresses">Addresses</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_contact_persons">Contact
                                Persons</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_portal_details">Portal
                                Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_4">Remarks</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="kt_tab_pane_other_details" role="tabpanel">
                            <div class="row mb-7">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="pan" class="form-control" id="pan"
                                            placeholder="PAN" value="{{ old('pan', $contact->pan) }}" />
                                        <label for="pan">PAN</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="gst" class="form-control" id="gst"
                                            placeholder="GST" value="{{ old('gst', $contact->gst) }}" />
                                        <label for="gst">GST</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-7">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="msme_registration_id" class="form-control"
                                            id="msme_registration_id" placeholder="MSME Registration ID"
                                            value="{{ old('msme_registration_id', $contact->msme_registration_id) }}" />
                                        <label for="msme_registration_id">MSME Registration ID</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" step="0.01" name="opening_balance"
                                            class="form-control" id="opening_balance" placeholder="Opening Balance"
                                            value="{{ old('opening_balance', $contact->opening_balance) }}"
                                            required />
                                        <label for="opening_balance">Opening Balance</label>
                                    </div>
                                </div>
                            </div>
                            <div class="separator border-2 my-10"></div>

                            <div class="row mb-7">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" name="payment_terms" class="form-control"
                                            id="payment_terms" placeholder="Payment Terms (Days)"
                                            value="{{ old('payment_terms', $contact->payment_terms) }}" required />
                                        <label for="payment_terms">Payment Terms (Days)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" step="0.01" name="credit_limit"
                                            class="form-control" id="credit_limit" placeholder="Credit Limit"
                                            value="{{ old('credit_limit', $contact->credit_limit) }}" required />
                                        <label for="credit_limit">Credit Limit</label>
                                    </div>
                                </div>
                            </div>

                            <!--begin::Row-->
                            <div class="row mb-7">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select name="default_currency" class="form-select" id="default_currency"
                                            required>
                                            <option value="" disabled>Select Currency</option>
                                            @foreach (config('globals.currencies') as $code => $name)
                                                <option value="{{ $code }}" @selected(old('default_currency', $contact->default_currency) == $code)>
                                                    {{ $name }} ({{ $code }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="default_currency">Default Currency</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select name="tds_id" class="form-select" data-control="select2"
                                            data-placeholder="Select TDS" id="tds_id">
                                            <option></option>
                                            @foreach ($tdsList as $tds)
                                                <option value="{{ $tds->id }}" @selected(old('tds_id', $contact->tds_id) == $tds->id)>
                                                    {{ $tds->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="tds_id">TDS</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="kt_tab_pane_contact_addresses" role="tabpanel">
                            <!--begin::Repeater for Addresses-->
                            <div id="kt_docs_repeater_addresses">
                                <div data-repeater-list="addresses">
                                    <!-- Hidden template -->
                                    <div data-repeater-item style="display: none;">
                                        <input type="hidden" name="addresses[][id]" value="">
                                        <div class="form-group row mb-5">
                                            <div class="col-md-3 mb-7">
                                                <div class="form-floating">
                                                    <select name="addresses[][label]" class="form-select">
                                                        <option value="" disabled selected>Select Label</option>
                                                        <option value="Billing">Billing</option>
                                                        <option value="Shipping">Shipping</option>
                                                    </select>
                                                    <label>Label</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-7">
                                                <div class="form-floating"><input type="text"
                                                        name="addresses[][address_line1]" class="form-control"
                                                        placeholder="Address Line 1"><label>Address Line 1</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-7">
                                                <div class="form-floating"><input type="text"
                                                        name="addresses[][address_line2]" class="form-control"
                                                        placeholder="Address Line 2"><label>Address Line 2</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2 mb-7">
                                                <div class="form-floating"><input type="text"
                                                        name="addresses[][city]"
                                                        class="form-control googleMapsTypeAheadCity"
                                                        placeholder="City"><label>City</label></div>
                                            </div>
                                            <div class="col-md-2 mb-7">
                                                <div class="form-floating"><input type="text"
                                                        name="addresses[][state]"
                                                        class="form-control googleMapsTypeAheadState"
                                                        placeholder="State"><label>State</label></div>
                                            </div>
                                            <div class="col-md-2 mb-7">
                                                <div class="form-floating"><input type="text"
                                                        name="addresses[][country]"
                                                        class="form-control googleMapsTypeAheadCountry"
                                                        placeholder="Country"><label>Country</label></div>
                                            </div>
                                            <div class="col-md-2 mb-7">
                                                <div class="form-floating"><input type="text"
                                                        name="addresses[][pincode]" class="form-control"
                                                        placeholder="Pincode"><label>Pincode</label></div>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-center mb-7"><a
                                                    href="javascript:;" data-repeater-delete
                                                    class="btn btn-sm btn-light-danger"><i
                                                        class="ki-duotone ki-trash fs-5"></i> Delete</a></div>
                                            <div class="separator my-5"></div>
                                        </div>
                                    </div>
                                    <!-- Render existing address rows -->
                                    @foreach ($addresses as $address)
                                        <div data-repeater-item>
                                            <input type="hidden" name="addresses[][id]"
                                                value="{{ $address['id'] ?? '' }}">
                                            <div class="form-group row mb-5">
                                                <div class="col-md-3 mb-7">
                                                    <div class="form-floating">
                                                        <select name="addresses[][label]" class="form-select">
                                                            <option value="" disabled>Select Label</option>
                                                            <option value="Billing" @selected(($address['label'] ?? '') === 'Billing')>
                                                                Billing</option>
                                                            <option value="Shipping" @selected(($address['label'] ?? '') === 'Shipping')>
                                                                Shipping</option>
                                                        </select>
                                                        <label>Label</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-7">
                                                    <div class="form-floating"><input type="text"
                                                            name="addresses[][address_line1]" class="form-control"
                                                            placeholder="Address Line 1"
                                                            value="{{ $address['address_line1'] ?? '' }}"><label>Address
                                                            Line 1</label></div>
                                                </div>
                                                <div class="col-md-3 mb-7">
                                                    <div class="form-floating"><input type="text"
                                                            name="addresses[][address_line2]" class="form-control"
                                                            placeholder="Address Line 2"
                                                            value="{{ $address['address_line2'] ?? '' }}"><label>Address
                                                            Line 2</label></div>
                                                </div>
                                                <div class="col-md-2 mb-7">
                                                    <div class="form-floating "><input type="text"
                                                            name="addresses[][city]"
                                                            class="form-control googleMapsTypeAheadCity"
                                                            placeholder="City"
                                                            value="{{ $address['city'] ?? '' }}"><label>City</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 mb-7">
                                                    <div class="form-floating"><input type="text"
                                                            name="addresses[][state]"
                                                            class="form-control googleMapsTypeAheadState"
                                                            placeholder="State"
                                                            value="{{ $address['state'] ?? '' }}"><label>State</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 mb-7">
                                                    <div class="form-floating"><input type="text"
                                                            name="addresses[][country]"
                                                            class="form-control googleMapsTypeAheadCountry"
                                                            placeholder="Country"
                                                            value="{{ $address['country'] ?? '' }}"><label>Country</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 mb-7">
                                                    <div class="form-floating"><input type="text"
                                                            name="addresses[][pincode]" class="form-control"
                                                            placeholder="Pincode"
                                                            value="{{ $address['pincode'] ?? '' }}"><label>Pincode</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 d-flex align-items-center mb-7"><a
                                                        href="javascript:;" data-repeater-delete
                                                        class="btn btn-sm btn-light-danger"><i
                                                            class="ki-duotone ki-trash fs-5"></i> Delete</a></div>
                                                <div class="separator my-5"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-group mt-5"><a href="javascript:;" data-repeater-create
                                        class="btn btn-light-primary"><i class="ki-duotone ki-plus fs-3"></i> Add</a>
                                </div>
                            </div>
                            <!--end::Repeater-->
                        </div>
                        <div class="tab-pane fade" id="kt_tab_pane_contact_persons" role="tabpanel">
                            <!--begin::Repeater-->
                            <div id="kt_docs_repeater_basic">
                                <div data-repeater-list="contact_persons">
                                    <!-- Hidden template for new contact person -->
                                    <div data-repeater-item style="display: none;">
                                        <div class="form-group row mb-5">
                                            <div class="col-md-2">
                                                <div class="form-floating">
                                                    <input type="text" name="contact_persons[][name]"
                                                        class="form-control" placeholder="Full Name">
                                                    <label>Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-floating">
                                                    <input type="text" name="contact_persons[][designation]"
                                                        class="form-control" placeholder="Designation">
                                                    <label>Designation</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-floating">
                                                    <input type="email" name="contact_persons[][email]"
                                                        class="form-control" placeholder="Email">
                                                    <label>Email</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-floating">
                                                    <input type="text" name="contact_persons[][phone]"
                                                        class="form-control" placeholder="Phone">
                                                    <label>Phone</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-floating">
                                                    <input type="text" name="contact_persons[][notes]"
                                                        class="form-control" placeholder="Notes">
                                                    <label>Notes</label>
                                                </div>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-center mt-3">
                                                <a href="javascript:;" data-repeater-delete
                                                    class="btn btn-sm btn-light-danger">
                                                    <i class="ki-duotone ki-trash fs-5"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach ($contactPersons as $person)
                                        <div data-repeater-item>
                                            <div class="form-group row mb-5">
                                                <div class="col-md-2">
                                                    <div class="form-floating">
                                                        <input type="text" name="contact_persons[][name]"
                                                            class="form-control" placeholder="Full Name"
                                                            value="{{ $person['name'] ?? '' }}">
                                                        <label>Name</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-floating">
                                                        <input type="text" name="contact_persons[][designation]"
                                                            class="form-control" placeholder="Designation"
                                                            value="{{ $person['designation'] ?? '' }}">
                                                        <label>Designation</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-floating">
                                                        <input type="email" name="contact_persons[][email]"
                                                            class="form-control" placeholder="Email"
                                                            value="{{ $person['email'] ?? '' }}">
                                                        <label>Email</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-floating">
                                                        <input type="text" name="contact_persons[][phone]"
                                                            class="form-control" placeholder="Phone"
                                                            value="{{ $person['phone'] ?? '' }}">
                                                        <label>Phone</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-floating">
                                                        <input type="text" name="contact_persons[][notes]"
                                                            class="form-control" placeholder="Notes"
                                                            value="{{ $person['notes'] ?? '' }}">
                                                        <label>Notes</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 d-flex align-items-center mt-3">
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
                                        <i class="ki-duotone ki-plus fs-3"></i>
                                        Add
                                    </a>
                                </div>
                            </div>
                            <!--end::Repeater-->
                        </div>

                        <div class="tab-pane fade" id="kt_tab_pane_portal_details" role="tabpanel">
                            <!--begin::Row-->
                            <div class="row mb-7">
                                <div class="col-md-6">
                                    <div class="form-check form-switch form-check-custom form-check-solid mt-3">
                                        <input class="form-check-input" type="checkbox" name="is_portal_enabled"
                                            value="1" @checked(old('is_portal_enabled', $contact->is_portal_enabled)) />
                                        <label class="form-check-label">Portal Access</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="portal_password" class="form-control"
                                            id="portal_password" placeholder="Portal Password"
                                            value="{{ old('portal_password', $contact->portal_password) }}" />
                                        <label for="portal_password">Portal Password</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="kt_tab_pane_4" role="tabpanel">
                            <div class="row mb-7">
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <textarea name="notes" class="form-control" id="notes" placeholder="Notes (For Internal Use)"
                                            style="height: 100px">{{ old('notes', $contact->notes) }}</textarea>
                                        <label for="notes">Notes (For Internal Use)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Actions-->
            <div class="d-flex justify-content-end">
                <a href="{{ route('contacts.index') }}" class="btn btn-light me-3">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    {{ $contact->exists ? 'Update Contact' : 'Create Contact' }}
                </button>
            </div>
        </form>
    </div>
@push('scripts')
    <script src="/assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>

    <!-- Load the Maps JS after defining initAutocomplete on window -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBAsbejB4-pxQ3I9pauRBd3-u-znIY2_zw&libraries=places&callback=initAutocomplete"
        async defer></script>
    <script>
        // Function to initialize Autocomplete with component-specific restrictions
        window.initAutocomplete = function () {
            // Helper function to initialize Autocomplete for a given input and type
            function initializeAutocomplete(input, type, componentType) {
                if (!input.dataset.autocompleteInitialized) {
                    const options = {
                        fields: ['address_components']
                    };
                    if (type === 'city') {
                        options.types = ['(cities)'];
                    } else if (type === 'state') {
                        options.types = ['(regions)'];
                    } else if (type === 'country') {
                        options.types = ['country'];
                    }

                    try {
                        const autocomplete = new google.maps.places.Autocomplete(input, options);
                        autocomplete.addListener('place_changed', () => {
                            const place = autocomplete.getPlace();
                            let value = '';
                            for (const component of place.address_components) {
                                if (component.types.includes(componentType)) {
                                    value = component.long_name;
                                    break;
                                }
                            }
                            input.value = value;
                        });
                        input.dataset.autocompleteInitialized = true;
                        console.log(`${type} Autocomplete initialized for:`, input);
                    } catch (error) {
                        console.error(`Error initializing ${type} Autocomplete:`, error);
                    }
                }
            }

            // Initialize City Autocomplete
            Array.from(document.getElementsByClassName('googleMapsTypeAheadCity')).forEach(input => {
                initializeAutocomplete(input, 'city', 'locality');
            });

            // Initialize State Autocomplete
            Array.from(document.getElementsByClassName('googleMapsTypeAheadState')).forEach(input => {
                initializeAutocomplete(input, 'state', 'administrative_area_level_1');
            });

            // Initialize Country Autocomplete
            Array.from(document.getElementsByClassName('googleMapsTypeAheadCountry')).forEach(input => {
                initializeAutocomplete(input, 'country', 'country');
            });
        };
    </script>
    <script>
        // Once the page and repeater plugin are ready…
        KTUtil.onDOMContentLoaded(function() {
            // Prevent duplicate repeater initialization
            if ($('#kt_docs_repeater_basic').data('repeater-initialized')) {
                console.warn('Basic repeater already initialized, skipping.');
                return;
            }
            if ($('#kt_docs_repeater_addresses').data('repeater-initialized')) {
                console.warn('Addresses repeater already initialized, skipping.');
                return;
            }

            // Initialize Autocomplete for existing inputs
            window.initAutocomplete();

            // Contact Persons repeater
            $('#kt_docs_repeater_basic').repeater({
                initEmpty: false,
                show: function() {
                    const $item = $(this);
                    $item.slideDown();
                    setTimeout(() => {
                        const newInputs = $item.find('.googleMapsTypeAheadCity, .googleMapsTypeAheadState, .googleMapsTypeAheadCountry');
                        console.log('New row inputs found (basic repeater):', newInputs.length);
                        if (newInputs.length > 0) {
                            window.initAutocomplete();
                        } else {
                            console.warn('No Autocomplete inputs found in new row (basic repeater). Check repeater template.');
                        }
                    }, 500);
                },
                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            }).data('repeater-initialized', true);

            // Addresses repeater
            $('#kt_docs_repeater_addresses').repeater({
                initEmpty: false,
                show: function() {
                    const $item = $(this);
                    $item.slideDown();
                    setTimeout(() => {
                        const newInputs = $item.find('.googleMapsTypeAheadCity, .googleMapsTypeAheadState, .googleMapsTypeAheadCountry');
                        console.log('New row inputs found (addresses repeater):', newInputs.length);
                        if (newInputs.length > 0) {
                            window.initAutocomplete();
                        } else {
                            console.warn('No Autocomplete inputs found in new row (addresses repeater). Check repeater template.');
                        }
                    }, 500);
                },
                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            }).data('repeater-initialized', true);
        });
    </script>
@endpush

</x-default-layout>
