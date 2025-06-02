<?php

namespace App\Http\Controllers\Apps\System;

use App\DataTables\System\CustomFieldDataTable;
use App\Http\Controllers\Controller;
use App\Models\CustomField;
use Illuminate\Http\Request;

class CustomFieldManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CustomFieldDataTable $dataTable)
    {
        return $dataTable->render('pages.apps.system.custom-fields.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomField $customField)
    {
        return view('pages.apps.system.custom-fields.show', compact('customField'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomField $customField)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomField $customField)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomField $customField)
    {
        //
    }
}
