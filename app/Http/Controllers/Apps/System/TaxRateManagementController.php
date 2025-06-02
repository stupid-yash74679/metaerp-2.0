<?php

namespace App\Http\Controllers\Apps\System;

use App\DataTables\System\TaxRateDataTable;
use App\Http\Controllers\Controller;
use App\Models\System\TaxRate; // Ensure this path is correct
use Illuminate\Http\Request;

class TaxRateManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TaxRateDataTable $dataTable)
    {
        // $this->authorize('viewAny', TaxRate::class); // Optional: Add authorization
        return $dataTable->render('pages.apps.system.tax-rates.list');
    }

    /**
     * Show the form for creating a new resource.
     * Typically handled by a Livewire modal, so this might not be used directly.
     */
    public function create()
    {
        // $this->authorize('create', TaxRate::class); // Optional: Add authorization
        // return view('pages.apps.system.tax-rates.create'); // If you have a separate create page
        return redirect()->route('system.tax-rates.index'); // Or redirect if using modal
    }

    /**
     * Store a newly created resource in storage.
     * Typically handled by Livewire component.
     */
    public function store(Request $request)
    {
        // $this->authorize('create', TaxRate::class); // Optional: Add authorization
        // Validation and store logic here if not using Livewire for submission
        // This will likely be handled by the Livewire component's save method
        return redirect()->route('system.tax-rates.index')->with('success', 'Tax Rate created successfully.');
    }

    /**
     * Display the specified resource.
     * This might not be needed if all info is in the list or edit modal.
     */
    public function show(TaxRate $taxRate)
    {
        // $this->authorize('view', $taxRate); // Optional: Add authorization
        // return view('pages.apps.system.tax-rates.show', compact('taxRate'));
        return redirect()->route('system.tax-rates.index'); // Or redirect if not using a show page
    }

    /**
     * Show the form for editing the specified resource.
     * Typically handled by a Livewire modal.
     */
    public function edit(TaxRate $taxRate)
    {
        // $this->authorize('update', $taxRate); // Optional: Add authorization
        // This will likely be handled by the Livewire component dispatching an event
        // return view('pages.apps.system.tax-rates.edit', compact('taxRate')); // If you have a separate edit page
        return redirect()->route('system.tax-rates.index'); // Or redirect if using modal
    }

    /**
     * Update the specified resource in storage.
     * Typically handled by Livewire component.
     */
    public function update(Request $request, TaxRate $taxRate)
    {
        // $this->authorize('update', $taxRate); // Optional: Add authorization
        // Validation and update logic here if not using Livewire for submission
        // This will likely be handled by the Livewire component's save method
        return redirect()->route('system.tax-rates.index')->with('success', 'Tax Rate updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * Typically handled by Livewire component or direct AJAX call from DataTable.
     */
    public function destroy(TaxRate $taxRate)
    {
        // $this->authorize('delete', $taxRate); // Optional: Add authorization
        // This will likely be handled by the Livewire component's delete method
        // Or a direct call from the DataTable action button if preferred.
        // $taxRate->delete();
        // return response()->json(['success' => 'Tax Rate deleted successfully.']);
        return redirect()->route('system.tax-rates.index')->with('success', 'Tax Rate deletion initiated.');
    }
}
