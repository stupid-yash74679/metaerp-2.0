<?php

namespace App\Livewire\System\MessageTemplate;

use App\Models\MessageTemplate;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AddEditMessageTemplate extends Component
{
    public $templateId;
    public $name;
    public $channel;
    public $subject;
    public $content;
    public $variables = ''; // Stays as a string, JS will sync with Tagify
    public $edit_mode = false;

    public $availableChannels = ['email', 'whatsapp', 'sms', 'app_push'];

    protected function rules()
    {
        return [
            'name'      => ['required', 'string', 'max:255', Rule::unique('message_templates', 'name')->ignore($this->templateId)],
            'channel'   => 'required|string|in:' . implode(',', $this->availableChannels),
            'subject'   => 'nullable|string|max:255',
            'content'   => 'required|string',
            'variables' => 'nullable|string',
        ];
    }

    protected $listeners = [
        'delete_message_template' => 'deleteMessageTemplate',
        'editMessageTemplate'    => 'loadMessageTemplate',
        'new_message_template'    => 'hydrateForm',
    ];

    // This method is called when the component is updated, including after loading data.
    // We can use this to signal JS after an edit load.
    public function rendering(): void // Or updated($propertyName) if specific property causes re-render
    {
        // Dispatch event for JS to re-initialize Tagify if necessary
        // This might fire too often. A more targeted event is better.
        // $this->dispatch('messageTemplateFormReady');
    }


    public function render()
    {
        return view('livewire.system.message-template.add-edit-message-template');
    }

    public function save()
    {
        $this->validate(array_merge($this->rules(), [
            'subject' => $this->channel === 'email' ? 'required|string|max:255' : 'nullable|string|max:255',
        ]));

        DB::transaction(function () {
            $variablesArray = [];
            if (!empty($this->variables)) {
                // Split by comma, trim, and filter out empty strings
                $variablesArray = array_filter(array_map('trim', explode(',', $this->variables)), function($value) {
                    return $value !== '';
                });
            }

            $data = [
                'name'      => $this->name,
                'channel'   => $this->channel,
                'subject'   => $this->channel === 'email' ? $this->subject : null,
                'content'   => $this->content,
                'variables' => $variablesArray, // Save as array
            ];

            if ($this->edit_mode) {
                $template = MessageTemplate::find($this->templateId);
                if ($template) {
                    $template->update($data);
                }
            } else {
                $data['created_by'] = Auth::id();
                $template = MessageTemplate::create($data);
            }

            // log_activity(...) // Assuming you have this helper

            $this->dispatch('success', $this->edit_mode ? 'Message template updated' : 'New message template created');
        });

        $this->dispatch('close-modal', 'kt_modal_add_message_template');
        $this->resetForm();
    }

    public function deleteMessageTemplate($id)
    {
        // ... (keep existing delete logic)
        $template = MessageTemplate::find($id);
        if ($template) {
            // log_activity(...)
            $template->delete();
        }
        $this->dispatch('success', 'Message template successfully deleted');
    }

    public function loadMessageTemplate($id)
    {
        $this->edit_mode = true;
        $template = MessageTemplate::findOrFail($id);

        $this->templateId = $template->id;
        $this->name       = $template->name;
        $this->channel    = $template->channel;
        $this->subject    = $template->subject;
        $this->content    = $template->content;
        $this->variables  = $template->variables ? implode(', ', $template->variables) : '';

        // Dispatch an event to tell JavaScript to re-initialize Tagify with new values
        $this->dispatch('messageTemplateFormReady');
    }

    public function hydrateForm() // Called when 'new_message_template' is dispatched or discard is clicked
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->resetForm();
        // Dispatch an event to tell JavaScript to re-initialize/clear Tagify
        $this->dispatch('messageTemplateFormReady');
    }

    public function resetForm()
    {
        $this->reset([
            'templateId', 'name', 'channel', 'subject', 'content', 'variables', 'edit_mode'
        ]);
        $this->variables = ''; // Explicitly clear for JS
    }
}
