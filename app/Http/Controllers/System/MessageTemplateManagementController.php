<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\MessageTemplate;
use App\DataTables\MessageTemplateDataTable; // Ensure correct namespace
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageTemplateManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(MessageTemplateDataTable $dataTable)
    {
        return $dataTable->render('pages.apps.system.message-templates.list');
    }

    /**
     * Show the form for creating a new resource.
     * (Handled by Livewire modal, so this might not be directly used if only modal is an option)
     */
    public function create()
    {
        // return view('pages.apps.system.message-templates.create');
        // Typically, for modal-only, this is not needed.
        // If you want a dedicated create page, you'd create this view.
        abort(404); // Or redirect to index if using modal only
    }

    /**
     * Store a newly created resource in storage.
     * (Handled by Livewire component's save method)
     */
    public function store(Request $request)
    {
        // This logic will be in the Livewire component.
        // If you need a non-Livewire way, you'd implement it here.
        abort(404);
    }

    /**
     * Display the specified resource.
     * (Optional - useful for a dedicated view page per template)
     */
    public function show(MessageTemplate $messageTemplate)
    {
        // return view('pages.apps.system.message-templates.show', compact('messageTemplate'));
        // For now, we are focusing on modal-based editing.
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     * (Handled by Livewire modal which gets data via an event)
     */
    public function edit(MessageTemplate $messageTemplate)
    {
        // This is typically handled by the modal opening and Livewire component fetching data.
        // return view('pages.apps.system.message-templates.edit', compact('messageTemplate'));
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     * (Handled by Livewire component's save method)
     */
    public function update(Request $request, MessageTemplate $messageTemplate)
    {
        // This logic will be in the Livewire component.
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     * (Can be handled by Livewire or a dedicated delete route if preferred)
     */
    public function destroy(MessageTemplate $messageTemplate)
    {
        // This is typically handled by the modal confirming delete and Livewire component performing action.
        // If you need direct delete from controller:
        // $messageTemplate->delete();
        // return redirect()->route('system.message-templates.index')->with('success', 'Message template deleted successfully.');
        abort(404);
    }
}
