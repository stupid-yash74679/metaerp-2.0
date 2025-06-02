<?php

namespace App\Http\Controllers\Apps\Contacts;

use App\DataTables\Contacts\ContactGroupDataTable;
use App\Http\Controllers\Controller;
use App\Models\ContactGroup;
use Illuminate\Http\Request;

class ContactGroupManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ContactGroupDataTable $dataTable)
    {
        return $dataTable->render('pages/apps.contacts.contact-groups.list');
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
    public function show(ContactGroup $contactGroup)
    {
        return view('pages/apps.contacts.contact-groups.show', compact('contactGroup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactGroup $contactGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContactGroup $contactGroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactGroup $contactGroup)
    {
        //
    }
}
