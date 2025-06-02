<?php

namespace App\Livewire\Apps\Projects\ProjectType;

use App\Models\Projects\ProjectType;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AddEditProjectType extends Component
{
    public $projectTypeId;
    public $name;
    public $description;
    public $is_active = true;
    public $stages = [];

    public $edit_mode = false;

    // Predefined color palette for new stages
    private $stageColorPalette = [
        '#007bff', '#6f42c1', '#e83e8c', '#dc3545', '#fd7e14', '#ffc107',
        '#28a745', '#20c997', '#17a2b8', '#6c757d', '#343a40', '#00796b', '#8BC34A',
        '#CDDC39', '#FFEB3B', '#FF9800', '#F44336', '#E91E63', '#9C27B0'
    ];
    public $nextColorIndex = 0; // MODIFIED: Made public to persist across Livewire updates

    protected function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255', Rule::unique('project_types', 'name')->ignore($this->projectTypeId)],
            'description' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
            'stages' => 'present|array',
        ];

        if (is_array($this->stages)) {
            foreach ($this->stages as $index => $stage) {
                $rules["stages.{$index}.id"] = 'required|string';
                $rules["stages.{$index}.name"] = 'required|string|max:255';
                $rules["stages.{$index}.description"] = 'nullable|string|max:1000';
                $rules["stages.{$index}.color"] = 'nullable|string|max:7';
                $rules["stages.{$index}.is_default_start"] = 'boolean';
                $rules["stages.{$index}.is_default_end"] = 'boolean';
            }
        }
        return $rules;
    }

    protected $messages = [
        'name.required' => 'The project type name is required.',
        'name.unique' => 'This project type name already exists.',
        'stages.*.name.required' => 'Each stage must have a name.',
    ];

    protected $listeners = [
        'editProjectType' => 'loadProjectType',
        'newProjectType' => 'hydrateForm',
        'deleteProjectTypeConfirmed' => 'deleteProjectType'
    ];

    public function mount()
    {
        if (!is_array($this->stages)) {
            $this->stages = [];
        }
        if (!$this->edit_mode && empty($this->stages)) {
            // $this->addStage(); // Optionally add first stage on mount for new
        }
        $this->programmaticallySetStartEndStages();
    }

    private function getNextColor(): string
    {
        $color = $this->stageColorPalette[$this->nextColorIndex % count($this->stageColorPalette)];
        $this->nextColorIndex++; // This will now increment the public property
        return $color;
    }

    private function ensureStageHasDefaultKeys(array &$stage, $index, bool $isNew = false): void
    {
        $stage['id'] = $stage['id'] ?? (string) Str::uuid();
        $stage['name'] = $stage['name'] ?? '';
        $stage['description'] = $stage['description'] ?? '';
        $stage['order'] = $index + 1;
        if ($isNew) {
            $stage['color'] = $this->getNextColor(); // Assign new color from palette
        } else {
            $stage['color'] = $stage['color'] ?? '#6c757d'; // Keep existing color or default for loaded
        }
        $stage['is_default_start'] = (bool) ($stage['is_default_start'] ?? false);
        $stage['is_default_end'] = (bool) ($stage['is_default_end'] ?? false);
    }

    public function addStage()
    {
        $newStageData = [];
        // Pass the current count as the index for the new stage being created
        $this->ensureStageHasDefaultKeys($newStageData, count($this->stages), true); // true for isNew
        $this->stages[] = $newStageData;
        $this->programmaticallySetStartEndStages();
    }

    public function removeStage($index)
    {
        if (!isset($this->stages[$index])) {
            return;
        }
        unset($this->stages[$index]);
        $this->stages = array_values($this->stages);
        $this->programmaticallySetStartEndStages();
    }

    public function moveStageUp($index)
    {
        if ($index > 0 && isset($this->stages[$index]) && isset($this->stages[$index - 1])) {
            $temp = $this->stages[$index - 1];
            $this->stages[$index - 1] = $this->stages[$index];
            $this->stages[$index] = $temp;
            $this->programmaticallySetStartEndStages();
        }
    }

    public function moveStageDown($index)
    {
        if (isset($this->stages[$index]) && $index < count($this->stages) - 1 && isset($this->stages[$index + 1])) {
            $temp = $this->stages[$index + 1];
            $this->stages[$index + 1] = $this->stages[$index];
            $this->stages[$index] = $temp;
            $this->programmaticallySetStartEndStages();
        }
    }

    private function programmaticallySetStartEndStages()
    {
        if (!is_array($this->stages) || empty($this->stages)) {
            return;
        }
        foreach ($this->stages as $i => &$stage) {
            $stage['order'] = $i + 1;
            $stage['is_default_start'] = ($i === 0);
            $stage['is_default_end'] = ($i === count($this->stages) - 1);
        }
    }

    public function render()
    {
        return view('livewire.apps.projects.project-type.add-edit-project-type');
    }

    public function saveProjectType()
    {
        $this->programmaticallySetStartEndStages();
        $this->validate();

        if (empty($this->stages)) {
            $this->addError('stages_general_error', 'At least one stage is required.');
            return;
        }
        // Default start is now programmatically handled, so no specific validation needed here

        DB::transaction(function () {
            $data = [
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
                'stages' => $this->stages,
            ];

            if ($this->edit_mode && $this->projectTypeId) {
                $projectType = ProjectType::find($this->projectTypeId);
                if ($projectType) {
                    $projectType->update($data);
                }
                $this->dispatch('success', ['message' => 'Project type updated successfully.']);
            } else {
                $data['created_by'] = Auth::id();
                ProjectType::create($data);
                $this->dispatch('success', ['message' => 'New project type created successfully.']);
            }
        });

        $this->dispatch('close-modal', 'kt_modal_add_edit_project_type');
        $this->resetForm();
    }

    public function loadProjectType($id = null)
    {
        if (!$id) {
            Log::error('loadProjectType called without an ID.', ['payload_id' => $id]);
            $this->dispatch('error', 'Invalid ID provided for editing project type.');
            $this->hydrateForm();
            return;
        }

        $this->resetValidation();
        $projectType = ProjectType::find($id);

        if (!$projectType) {
            $this->dispatch('error', "Project type with ID {$id} not found.");
            $this->hydrateForm();
            return;
        }

        $this->projectTypeId = $projectType->id;
        $this->name = $projectType->name;
        $this->description = $projectType->description;
        $this->is_active = (bool) $projectType->is_active;
        $this->edit_mode = true;
        $this->nextColorIndex = 0; // Reset color index when loading an existing item

        $dbStages = $projectType->stages;
        $processedStages = [];

        if (is_array($dbStages)) {
            foreach ($dbStages as $index => $stageData) {
                if (is_array($stageData)) {
                    $this->ensureStageHasDefaultKeys($stageData, $index, false); // false for isNew
                    $processedStages[] = $stageData;
                }
            }
        }
        $this->stages = $processedStages;
        $this->programmaticallySetStartEndStages();

        $this->dispatch('projectTypeFormReady', ['stages' => $this->stages]);
    }

    public function hydrateForm()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->resetForm();
        $this->dispatch('projectTypeFormReady', ['stages' => []]);
    }

    private function resetForm()
    {
        $this->projectTypeId = null;
        $this->name = '';
        $this->description = '';
        $this->is_active = true;
        $this->stages = [];
        $this->edit_mode = false;
        $this->nextColorIndex = 0; // Crucial: Reset color index for new/fresh forms
    }

    public function deleteProjectType($id = null)
    {
        if (!$id) {
            Log::error('deleteProjectType called without an ID.', ['payload_id' => $id]);
            $this->dispatch('error', 'Invalid ID provided for deleting project type.');
            return;
        }
        $projectType = ProjectType::find($id);
        if ($projectType) {
            $projectType->delete();
            $this->dispatch('success', ['message' => 'Project type deleted successfully.']);
        } else {
            $this->dispatch('error', 'Project type not found.');
        }
    }
}
