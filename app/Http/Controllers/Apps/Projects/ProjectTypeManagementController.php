<?php

namespace App\Http\Controllers\Apps\Projects;

use App\Http\Controllers\Controller;
use App\DataTables\Projects\ProjectTypeDataTable; // Adjusted namespace for DataTable
use App\Models\Projects\ProjectType; // Your ProjectType model
use Illuminate\Http\Request;

class ProjectTypeManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ProjectTypeDataTable $dataTable
     * @return \Illuminate\Http\Response // Should be \Illuminate\View\View or \Illuminate\Http\JsonResponse for DataTables
     */
    public function index(ProjectTypeDataTable $dataTable)
    {
        // Similar to CurrencyManagementController, render the DataTable view
        // The view path will be something like 'pages.apps.projects.project-types.list'
        return $dataTable->render('pages.apps.projects.project-types.list');
    }

    /**
     * Show the form for creating a new resource.
     * This will likely be handled by a Livewire modal, so this route might not be directly used.
     */
    public function create()
    {
        // If using a dedicated page: return view('pages.apps.projects.project-types.create');
    }

    /**
     * Store a newly created resource in storage.
     * This will be handled by the Livewire component.
     */
    public function store(Request $request)
    {
        // Logic will be in the Livewire component: AddEditProjectTypeModal.php
    }

    /**
     * Display the specified resource.
     * Usually not needed if editing is done via modal and details are in the list view.
     */
    public function show(ProjectType $projectType) // Route model binding
    {
        // If using a dedicated page: return view('pages.apps.projects.project-types.show', compact('projectType'));
    }

    /**
     * Show the form for editing the specified resource.
     * This will be handled by opening the Livewire modal with existing data.
     */
    public function edit(ProjectType $projectType)
    {
        // If using a dedicated page: return view('pages.apps.projects.project-types.edit', compact('projectType'));
    }

    /**
     * Update the specified resource in storage.
     * This will be handled by the Livewire component.
     */
    public function update(Request $request, ProjectType $projectType)
    {
        // Logic will be in the Livewire component: AddEditProjectTypeModal.php
    }

    /**
     * Remove the specified resource from storage.
     * This can be handled by the Livewire component or a dedicated route if preferred.
     */
    public function destroy(ProjectType $projectType)
    {
        // Logic can be in the Livewire component.
        // If handling here:
        // $projectType->delete();
        // return redirect()->route('projects.project-types.index')->with('success', 'Project Type deleted.');
    }
}
