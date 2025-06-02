<?php

namespace App\Http\Controllers\Apps\Contacts;

use App\DataTables\Contacts\ContactDataTable;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Tds;
use App\Models\CRM\Lead;
use Illuminate\Http\Request;

class ContactManagementController extends Controller
{
    /**
     * Display a listing of all contacts.
     */
    public function index(ContactDataTable $dataTable)
    {
        return $dataTable->render('pages/apps.contacts.contacts.list');
    }

    /**
     * Show the form to create a new contact.
     */
    public function add()
    {
        $contact       = new Contact();
        $contactGroups = ContactGroup::all();
        $tdsList       = Tds::all();
        $leads         = Lead::all();

        return view('pages/apps.contacts.contacts.show', compact('contact', 'contactGroups', 'tdsList', 'leads'));
    }

    /**
     * Show the form to view or edit an existing contact.
     */
    public function show(Contact $contact)
    {
        $contactGroups = ContactGroup::all();
        $tdsList       = Tds::all();
        $leads         = Lead::all();

        return view('pages/apps.contacts.contacts.show', compact('contact', 'contactGroups', 'tdsList', 'leads'));
    }

    /**
     * Store a newly created contact or update an existing contact in storage.
     */
    public function storeOrUpdate(Request $request, ?Contact $contact = null)
    {
        $rules = [
            'name'                  => 'required|string|max:255',
            'company_name'          => 'nullable|string|max:255',
            'email'                 => 'nullable|email',
            'phone'                 => 'nullable|string|max:20',
            'website'               => 'nullable|string',
            'pan'                   => 'nullable|string|max:20',
            'gst'                   => 'nullable|string|max:20',
            'msme_registration_id'  => 'nullable|string|max:100',
            'opening_balance'       => 'nullable|numeric',
            'payment_terms'         => 'nullable|integer',
            'credit_limit'          => 'nullable|numeric',
            'default_currency'      => 'nullable|string|max:10',
            'tds_id'                => 'nullable|exists:tds,id',
            'contact_type'          => 'nullable|in:individual,company',
            'contact_group_id'      => 'nullable|exists:contact_groups,id',
            'lead_id'               => 'nullable|exists:leads,id',
            'notes'                 => 'nullable|string',
            'status'                => 'nullable|string',
            'bank_details'          => 'nullable|string',
            'upi_id'                => 'nullable|string',
            'documents'             => 'nullable|array',
            'addresses'                     => 'nullable|array',
            'addresses.*.id'                => 'nullable|integer',
            'addresses.*.label'             => 'nullable|string|max:255',
            'addresses.*.address_line1'     => 'nullable|string|max:255',
            'addresses.*.address_line2'     => 'nullable|string|max:255',
            'addresses.*.city'              => 'nullable|string|max:100',
            'addresses.*.state'             => 'nullable|string|max:100',
            'addresses.*.country'           => 'nullable|string|max:100',
            'addresses.*.pincode'           => 'nullable|string|max:20',
            'portal_password'       => 'nullable|string',
            'contact_persons'               => 'nullable|array',
            'contact_persons.*.name'        => 'nullable|string|max:255',
            'contact_persons.*.designation' => 'nullable|string|max:255',
            'contact_persons.*.email'       => 'nullable|email',
            'contact_persons.*.phone'       => 'nullable|string|max:20',
            'contact_persons.*.notes'       => 'nullable|string',
        ];

        $data = $request->validate($rules);

        // Associate contact with a lead if provided
        $data['lead_id'] = $request->input('lead_id');

        // Normalize checkboxes
        $data['is_customer']       = $request->has('is_customer');
        $data['is_vendor']         = $request->has('is_vendor');
        $data['is_portal_enabled'] = $request->has('is_portal_enabled');

        // Always pull raw repeater input, then filter blank or whitespace-only entries
        $personsRaw = $request->input('contact_persons', []);
        $filtered = array_filter($personsRaw, function ($p) {
            $name  = isset($p['name']) ? trim($p['name']) : '';
            $email = isset($p['email']) ? trim($p['email']) : '';
            // keep if either trimmed name OR trimmed email is non-empty
            return ($name !== '' || $email !== '');
        });
        $data['contact_persons'] = array_values($filtered);

        // Always pull raw repeater input for addresses, then filter entries missing address_line1
        $addressesRaw = $request->input('addresses', []);
        $filteredAddresses = array_filter($addressesRaw, function ($a) {
            $line1 = isset($a['address_line1']) ? trim($a['address_line1']) : '';
            return $line1 !== '';
        });
        $data['addresses'] = array_values($filteredAddresses);

        // Force the authenticated user
        $data['user_id'] = auth()->id();

        // Either update or create
        if ($contact && $contact->exists) {
            $contact->fill($data)->save();
            $message = 'Contact updated successfully.';
        } else {
            $contact = Contact::create($data);
            $message = 'Contact created successfully.';
        }

        return redirect()
            ->route('contacts.index', $contact)
            ->with('success', $message);
    }
}
