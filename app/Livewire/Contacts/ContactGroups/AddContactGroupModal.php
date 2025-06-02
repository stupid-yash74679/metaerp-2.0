<?php

namespace App\Livewire\Contacts\ContactGroups;

use App\Models\ContactGroup;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;

class AddContactGroupModal extends Component
{
    public $contactGroupId;
    public $name;
    public $description;
    public $is_default = false;
    public $edit_mode = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'is_default' => 'boolean',
    ];

    protected $listeners = [
        'delete_contact_group' => 'deleteGroup',
        'editContactGroup' => 'updateGroup',
        'new_contact_group' => 'hydrateForm',
    ];

    public function render()
    {
        return view('livewire.contacts.contact-groups.add-contact-group-modal');
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $data = [
                'name' => $this->name,
                'description' => $this->description,
                'is_default' => $this->is_default,
            ];

            if (!$this->edit_mode) {
                $data['user_id'] = Auth::id();
            }

            $group = ContactGroup::find($this->contactGroupId) ?? ContactGroup::create($data);

            if ($this->edit_mode && $group) {
                foreach ($data as $key => $value) {
                    $group->$key = $value;
                }
                $group->save();
            }

            log_activity(
                $this->edit_mode ? 'update' : 'create',
                'ContactGroup',
                $this->edit_mode ? 'Updated contact group' : 'Created contact group',
                $group,
                $data
            );

            $this->dispatch('success', $this->edit_mode ? 'Contact Group updated' : 'New Contact Group created');
        });

        $this->dispatch('close-modal', 'kt_modal_add_contact_group');
        $this->reset();
    }

    public function deleteGroup($id)
    {
        $group = ContactGroup::find($id);
        if ($group) {
            log_activity(
                'delete',
                'ContactGroup',
                'Deleted contact group',
                $group,
                ['name' => $group->name]
            );
            $group->delete();
        }

        $this->dispatch('success', 'Contact Group successfully deleted');
    }

    public function updateGroup($id)
    {
        $this->edit_mode = true;

        $group = ContactGroup::findOrFail($id);
        $this->contactGroupId = $group->id;
        $this->name = $group->name;
        $this->description = $group->description;
        $this->is_default = $group->is_default;
    }

    public function hydrateForm()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset(['contactGroupId', 'name', 'description', 'is_default', 'edit_mode']);
    }
}
