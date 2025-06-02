<?php

namespace App\Http\Controllers\Apps\CRM;

use App\DataTables\CRM\LeadDataTable;
use App\Http\Controllers\Controller;
use App\Models\CRM\Lead;
use App\Models\CustomField;
use App\Services\MessagingService; // Import the MessagingService
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // For logging results
use App\Models\MessageTemplate; // Import MessageTemplate
use Carbon\Carbon; // Import Carbon

class LeadManagementController extends Controller
{
    protected MessagingService $messagingService;

    /**
     * Constructor to inject MessagingService.
     */
    public function __construct(MessagingService $messagingService)
    {
        $this->messagingService = $messagingService;
    }

    /**
     * Display a listing of leads.
     */
    public function index(LeadDataTable $dataTable)
    {
        return $dataTable->render('pages/apps.crm.leads.list');
    }

    /**
     * Show form to create a new lead.
     */
    public function add()
    {
        $lead = new Lead();
        $customFields = CustomField::where('module', 'Leads')->get();
        $emailTemplates = MessageTemplate::where('channel', 'email')->get();
        return view('pages/apps.crm.leads.show', compact('lead', 'customFields', 'emailTemplates'));
    }

    /**
     * Show form to edit an existing lead.
     */
    public function show(Lead $lead)
    {
        $customFields = CustomField::where('module', 'Leads')->get();
        $emailTemplates = MessageTemplate::where('channel', 'email')->get();
        return view('pages/apps.crm.leads.show', compact('lead', 'customFields', 'emailTemplates'));
    }

    /**
     * Store a newly created lead or update an existing one.
     */
    public function storeOrUpdate(Request $request, ?Lead $lead = null)
    {
        $rules = [
            'first_name'      => 'required|string|max:100',
            'last_name'       => 'required|string|max:100',
            'email'           => 'nullable|email|max:150',
            'phone'           => 'nullable|string|max:30',
            'company'         => 'nullable|string|max:150',
            'status'          => 'required|string|max:50',
            'source'          => 'nullable|string|max:50',
            'notes'           => 'nullable|string',
            'inquiry_about'   => 'nullable|string|max:255',
            'street'          => 'nullable|string|max:255',
            'city'            => 'nullable|string|max:100',
            'state'           => 'nullable|string|max:100',
            'country'         => 'nullable|string|max:100',
            'zip_code'        => 'nullable|string|max:20',
            'follow_ups'               => 'nullable|array',
            'follow_ups.*.type'        => 'nullable|string|max:100',
            'follow_ups.*.next_followup_date' => 'nullable|date',
            'follow_ups.*.task_by_lead' => 'nullable|string',
            'follow_ups.*.task_by_us'   => 'nullable|string',
            'follow_ups.*.notes'        => 'nullable|string',
            'follow_ups.*.user_id'      => 'nullable|exists:users,id',
            'meetings'                 => 'nullable|array',
            'meetings.*.meeting_date'  => 'nullable|date',
            'meetings.*.subject'       => 'nullable|string|max:150',
            'meetings.*.location'      => 'nullable|string|max:150',
            'meetings.*.attendees'     => 'nullable|array',
            'meetings.*.notes'         => 'nullable|string',
            'meetings.*.user_id'       => 'nullable|exists:users,id',
            'custom_fields'            => 'nullable|array',
        ];

        $validatedData = $request->validate($rules);
        $validatedData['owner_id'] = auth()->id();

        $rawF = $request->input('follow_ups', []);
        $filteredF = array_filter($rawF, function($f) {
            return !empty(trim($f['type'] ?? ''));
        });
        $validatedData['follow_ups'] = array_values($filteredF);

        $rawM = $request->input('meetings', []);
        $filteredM = array_filter($rawM, function($m) {
            return !empty(trim($m['subject'] ?? ''));
        });
        $validatedData['meetings'] = array_values($filteredM);

        $customFieldData = [];
        $customFieldsDefinitions = CustomField::where('module', 'Leads')->get();
        foreach ($customFieldsDefinitions as $fieldDef) {
            $value = $request->input("custom_fields.{$fieldDef->id}");
            if ($fieldDef->type === 'checkbox') {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            }
            if (!is_null($value) && ($value !== '' || $fieldDef->type === 'checkbox')) {
                $customFieldData[$fieldDef->id] = [
                    'id'    => $fieldDef->id,
                    'name'  => $fieldDef->name,
                    'label' => $fieldDef->label,
                    'value' => $value,
                ];
            }
        }
        $validatedData['custom_fields'] = $customFieldData;

        $isNewLead = false;
        $oldStatus = null;

        if ($lead && $lead->exists) {
            $oldStatus = $lead->status;
            $lead->fill($validatedData)->save();
            $message = 'Lead updated successfully.';
        } else {
            $lead = Lead::create($validatedData);
            $message = 'Lead created successfully.';
            $isNewLead = true;
        }

        $templateData = [
            'company_name'      => config('app.name', 'Our Company'),
            'lead_name'         => $lead->first_name . ' ' . $lead->last_name,
            'first_name'        => $lead->first_name,
            'last_name'         => $lead->last_name,
            'lead_email'        => $lead->email,
            'lead_phone'        => $lead->phone,
            'lead_company'      => $lead->company,
            'inquiry_about'     => $lead->inquiry_about,
            'enquiry_number'    => $lead->enquiry_number,
            'inquiry_details'   => $lead->inquiry_about,
            'lead_status'       => $lead->status,
            'lead_source'       => $lead->source,
            'lead_notes'        => $lead->notes,
        ];
        if ($lead->owner) {
            $templateData['owner_name'] = $lead->owner->name;
            $templateData['owner_email'] = $lead->owner->email;
        }

        // Schedule email notifications
        if ($isNewLead && $lead->email) {
            $scheduleResult = $this->messagingService->scheduleMessage(
                'email',
                $lead,
                'NewLeadWelcomeEmail',
                $templateData,
                Carbon::now() // Schedule for immediate processing by the queue worker
            );
            Log::info('New Lead Welcome Email scheduling result for lead ID ' . $lead->id . ': ', $scheduleResult);
        }

        if (!$isNewLead && $oldStatus !== $lead->status && $lead->status === 'Qualified' && $lead->email) {
            $scheduleResult = $this->messagingService->scheduleMessage(
                'email',
                $lead,
                'LeadQualifiedNotificationEmail',
                $templateData,
                Carbon::now() // Schedule for immediate processing
            );
            Log::info('Lead Qualified Email scheduling result for lead ID ' . $lead->id . ': ', $scheduleResult);
        }

        return redirect()
            ->route('leads.index')
            ->with('success', $message);
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()
            ->route('leads.index')
            ->with('success', 'Lead deleted successfully.');
    }
}
