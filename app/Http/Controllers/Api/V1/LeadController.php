<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CRM\Lead;
use App\Models\CustomField; // For custom field processing
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\LeadResource; // We will create this next
use App\Http\Resources\LeadCollection; // We will create this next
use Illuminate\Support\Facades\Auth;   // For owner_id
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Basic pagination, can be enhanced with filtering, sorting from request query parameters
        $leads = Lead::with('owner') // Eager load owner if needed in API response
                    ->paginate($request->input('per_page', 15));
        return new LeadCollection($leads);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'first_name'      => 'required|string|max:100',
            'last_name'       => 'required|string|max:100',
            'email'           => 'nullable|email|max:150|unique:leads,email', // Unique email for new leads
            'phone'           => 'nullable|string|max:30',
            'company'         => 'nullable|string|max:150',
            'status'          => 'required|string|max:50', // Consider using Rule::in(config('globals.statusValuesArray'))
            'source'          => 'nullable|string|max:50', // Consider using Rule::in(config('globals.sourceValuesArray'))
            'notes'           => 'nullable|string',
            'inquiry_about'   => 'nullable|string|max:255',
            'street'          => 'nullable|string|max:255',
            'city'            => 'nullable|string|max:100',
            'state'           => 'nullable|string|max:100',
            'country'         => 'nullable|string|max:100',
            'zip_code'        => 'nullable|string|max:20',
            'owner_id'        => 'nullable|exists:users,id',
            'follow_ups'      => 'nullable|array',
            'meetings'        => 'nullable|array',
            'custom_fields'   => 'nullable|array', // Expects custom_fields[field_id] = value
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validatedData = $validator->validated();

        // Assign owner if not provided, or use authenticated user if no owner_id in request
        $validatedData['owner_id'] = $validatedData['owner_id'] ?? Auth::id();

        // Process custom fields if present in the request
        if (isset($validatedData['custom_fields'])) {
            $customFieldInput = $validatedData['custom_fields'];
            $processedCustomFields = [];
            $customFieldDefinitions = CustomField::where('module', 'Leads')->get()->keyBy('id');

            foreach ($customFieldInput as $fieldId => $value) {
                if ($customFieldDefinitions->has($fieldId)) {
                    $fieldDef = $customFieldDefinitions->get($fieldId);
                    $processedValue = $value;
                    if ($fieldDef->type === 'checkbox') {
                        $processedValue = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    }
                    // Store in the desired format: id => [id, name, label, value]
                    $processedCustomFields[$fieldDef->id] = [
                        'id'    => $fieldDef->id,
                        'name'  => $fieldDef->name,
                        'label' => $fieldDef->label,
                        'value' => $processedValue,
                    ];
                }
            }
            $validatedData['custom_fields'] = $processedCustomFields;
        }


        $lead = Lead::create($validatedData);

        // Optionally, send a welcome email via MessagingService (if this API creates leads that should trigger it)
        // app(MessagingService::class)->scheduleMessage('email', $lead, 'NewLeadWelcomeEmail', [...], now());

        return new LeadResource($lead->load('owner'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        return new LeadResource($lead->load('owner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lead $lead)
    {
        $rules = [
            'first_name'      => 'sometimes|required|string|max:100',
            'last_name'       => 'sometimes|required|string|max:100',
            'email'           => 'nullable|email|max:150|unique:leads,email,' . $lead->id, // Ignore current lead's email
            'phone'           => 'nullable|string|max:30',
            'company'         => 'nullable|string|max:150',
            'status'          => 'sometimes|required|string|max:50',
            'source'          => 'nullable|string|max:50',
            'notes'           => 'nullable|string',
            'inquiry_about'   => 'nullable|string|max:255',
            'street'          => 'nullable|string|max:255',
            'city'            => 'nullable|string|max:100',
            'state'           => 'nullable|string|max:100',
            'country'         => 'nullable|string|max:100',
            'zip_code'        => 'nullable|string|max:20',
            'owner_id'        => 'nullable|exists:users,id',
            'follow_ups'      => 'nullable|array',
            'meetings'        => 'nullable|array',
            'custom_fields'   => 'nullable|array',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validatedData = $validator->validated();


        // Process custom fields if present in the request for update
        if (isset($validatedData['custom_fields'])) {
            $customFieldInput = $validatedData['custom_fields'];
            // Merge with existing custom fields, or replace. Here, we replace.
            $processedCustomFields = $lead->custom_fields ?? []; // Start with existing or empty
            $customFieldDefinitions = CustomField::where('module', 'Leads')->get()->keyBy('id');

            foreach ($customFieldInput as $fieldId => $value) {
                if ($customFieldDefinitions->has($fieldId)) {
                    $fieldDef = $customFieldDefinitions->get($fieldId);
                    $processedValue = $value;
                    if ($fieldDef->type === 'checkbox') {
                        $processedValue = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    }
                     $processedCustomFields[$fieldDef->id] = [
                        'id'    => $fieldDef->id,
                        'name'  => $fieldDef->name,
                        'label' => $fieldDef->label,
                        'value' => $processedValue,
                    ];
                }
            }
            $validatedData['custom_fields'] = $processedCustomFields;
        }


        $lead->update($validatedData);

        // Optionally, send notification on status change
        // if ($request->has('status') && $originalStatus !== $lead->status && $lead->status === 'Qualified') {
        //    app(MessagingService::class)->scheduleMessage('email', $lead, 'LeadQualifiedNotificationEmail', [...], now());
        // }

        return new LeadResource($lead->load('owner'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
