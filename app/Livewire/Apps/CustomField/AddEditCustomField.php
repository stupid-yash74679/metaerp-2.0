<?php

namespace App\Livewire\Apps\CustomField;

use App\Models\CustomField;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // Import the Rule class

class AddEditCustomField extends Component
{
    public $customFieldId;
    public $module;
    public $label;
    public $name;
    public $type;
    public $options;
    public $is_required;
    public $is_visible_in_table;
    public $order;
    public $edit_mode = false;

    public $availableModules = []; // New property for dropdown options

    protected $listeners = [
        'delete_custom_field' => 'deleteCustomField',
        'editCustomField'     => 'updateCustomField',
        'new_custom_field'    => 'hydrateForm',
    ];

    public function mount()
    {
        $this->is_required = false;
        $this->is_visible_in_table = false;

        // Define your available modules here
        $this->availableModules = [
            'Leads',
            'Contacts',
            'Projects',
            // Add more modules as needed based on your application structure
        ];
    }

    /**
     * Define validation rules as a method.
     */
    public function rules()
    {
        return [
            'module'              => 'required|string|max:255',
            'label'               => 'required|string|max:255',
            'name'                => [
                'required',
                'string',
                'max:255',
                Rule::unique('custom_fields', 'name')->ignore($this->customFieldId),
            ],
            'type'                => 'required|string|max:255',
            'options'             => 'nullable|string', // Will be json_decoded
            'is_required'         => 'boolean',
            'is_visible_in_table' => 'boolean',
            'order'               => 'nullable|integer',
        ];
    }

    public function render()
    {
        // Pass availableModules to the view
        return view('livewire.apps.custom-field.add-edit-custom-field', [
            'availableModules' => $this->availableModules,
        ]);
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $data = [
                'module'              => $this->module,
                'label'               => $this->label,
                'name'                => $this->name,
                'type'                => $this->type,
                'options'             => $this->options ? json_decode($this->options) : null,
                'is_required'         => $this->is_required,
                'is_visible_in_table' => $this->is_visible_in_table,
                'order'               => $this->order,
                'created_by'          => Auth::id(),
            ];

            if ($this->edit_mode) {
                $customField = CustomField::find($this->customFieldId);
                if ($customField) {
                    $customField->update($data);
                }
            } else {
                $customField = CustomField::create($data);
            }

            log_activity(
                $this->edit_mode ? 'update' : 'create',
                'CustomField',
                $this->edit_mode ? 'Updated custom field' : 'Created custom field',
                $customField,
                $data
            );

            $this->dispatch('success', $this->edit_mode ? 'Custom field updated' : 'New custom field created');
        });

        $this->dispatch('close-modal', 'kt_modal_add_custom_field');
        $this->reset();
    }

    public function deleteCustomField($id)
    {
        $customField = CustomField::find($id);
        if ($customField) {
            log_activity(
                'delete',
                'CustomField',
                'Deleted custom field',
                $customField,
                ['label' => $customField->label]
            );
            $customField->delete();
        }

        $this->dispatch('success', 'Custom field successfully deleted');
    }

    public function updateCustomField($id)
    {
        $this->edit_mode = true;

        $customField = CustomField::findOrFail($id);
        $this->customFieldId       = $customField->id;
        $this->module              = $customField->module;
        $this->label               = $customField->label;
        $this->name                = $customField->name;
        $this->type                = $customField->type;
        $this->options             = $customField->options ? json_encode($customField->options) : '';
        $this->is_required         = $customField->is_required;
        $this->is_visible_in_table = $customField->is_visible_in_table;
        $this->order               = $customField->order;
    }

    public function hydrateForm()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset([
            'customFieldId',
            'module',
            'label',
            'name',
            'type',
            'options',
            'is_required',
            'is_visible_in_table',
            'order',
            'edit_mode'
        ]);
        $this->is_required = false;
        $this->is_visible_in_table = false;
    }
}
