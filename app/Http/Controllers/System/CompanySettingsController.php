<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Currency; // Import your Currency model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // For file uploads
use Illuminate\Support\Facades\Log;

class CompanySettingsController extends Controller
{
    /**
     * Show the form for editing the company settings.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        // For a single company setup, we always fetch the first record or create it if it doesn't exist.
        $companySettings = Company::firstOrCreate(
            ['id' => 1], // Assuming ID 1 is always your company settings record
            [
                // Provide minimal defaults if creating for the very first time
                'name' => config('app.name', 'My Company'),
                'default_currency_code' => 'INR',
                'timezone' => config('app.timezone', 'Asia/Kolkata'),
                'date_format' => 'd-m-Y',
                'time_format' => 'h:i A',
                'financial_year_start_month' => 4,
                'social_links' => [], // Ensure it's an empty array if new
                'director_identification_numbers' => [],
            ]
        );

        $currencies = Currency::all(); // Fetch all currencies for the dropdown

        return view('pages.apps.system.company-settings.edit', compact('companySettings', 'currencies'));
    }

    /**
     * Update the company settings in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $companySettings = Company::firstOrFail(); // Or Company::find(1);

        // Define validation rules (add more as needed based on your model)
        $rules = [
            'name' => 'required|string|max:255',
            'display_name' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // For logo upload
            'favicon' => 'nullable|image|mimes:ico,png|max:512',          // For favicon upload
            'website_url' => 'nullable|url|max:255',
            'company_type' => 'nullable|string|max:255',
            'date_of_incorporation' => 'nullable|date',
            'description' => 'nullable|string',
            'primary_phone' => 'nullable|string|max:20',
            'secondary_phone' => 'nullable|string|max:20',
            'general_email' => 'nullable|email|max:255',
            'support_email' => 'nullable|email|max:255',
            'hr_email' => 'nullable|email|max:255',

            // Registered Address
            'registered_address_line1' => 'nullable|string|max:255',
            'registered_address_line2' => 'nullable|string|max:255',
            'registered_city' => 'nullable|string|max:100',
            'registered_state_province' => 'nullable|string|max:100',
            'registered_postal_code' => 'nullable|string|max:20',
            'registered_country' => 'nullable|string|max:100',

            // Tax Identifiers
            'pan_number' => 'nullable|string|size:10|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/i', // Basic PAN format
            'tan_number' => 'nullable|string|size:10|regex:/^[A-Z]{4}[0-9]{5}[A-Z]{1}$/i', // Basic TAN format
            'gstin_number' => 'nullable|string|size:15|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i', // Basic GSTIN format
            'cin_number' => 'nullable|string|size:21',
            'udyam_registration_number' => 'nullable|string|size:19', // URN format Udyam-XX-XX-XXXXXXX

            // Social Links (expecting an array from the form)
            'social_links.facebook' => 'nullable|url|max:255',
            'social_links.twitter' => 'nullable|url|max:255',
            'social_links.linkedin' => 'nullable|url|max:255',
            'social_links.instagram' => 'nullable|url|max:255',
            'social_links.youtube' => 'nullable|url|max:255',

            // Localization
            'default_currency_code' => 'nullable|string|exists:currencies,code', // Ensure code exists in currencies table
            'timezone' => 'nullable|string|max:100', // Consider using a timezone list for validation
            'financial_year_start_month' => 'nullable|integer|min:1|max:12',
            'date_format' => 'nullable|string|max:50',
            'time_format' => 'nullable|string|max:50',
        ];

        $validatedData = $request->validate($rules);

        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            // Delete old logo if it exists
            if ($companySettings->logo_path && Storage::disk('public')->exists($companySettings->logo_path)) {
                Storage::disk('public')->delete($companySettings->logo_path);
            }
            $logoPath = $request->file('logo')->store('company/logos', 'public');
            $validatedData['logo_path'] = $logoPath;
        }

        // Handle Favicon Upload
        if ($request->hasFile('favicon')) {
            if ($companySettings->favicon_path && Storage::disk('public')->exists($companySettings->favicon_path)) {
                Storage::disk('public')->delete($companySettings->favicon_path);
            }
            $faviconPath = $request->file('favicon')->store('company/favicons', 'public');
            $validatedData['favicon_path'] = $faviconPath;
        }

        // MSME Registered checkbox
        $validatedData['msme_registered'] = $request->has('msme_registered');

        // The social_links will be passed as an array and automatically cast to JSON by Eloquent.
        // If any social link is empty, we might want to remove it from the array to keep JSON clean.
        if (isset($validatedData['social_links'])) {
            $validatedData['social_links'] = array_filter($validatedData['social_links'], function ($value) {
                return !is_null($value) && $value !== '';
            });
        }


        try {
            $companySettings->update($validatedData);
            // Optional: Clear any settings cache you might have implemented
            // Cache::forget('company_settings');

            return redirect()->route('system.company-settings.edit')->with('success', 'Company settings updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating company settings: ' . $e->getMessage());
            return redirect()->route('system.company-settings.edit')->with('error', 'Failed to update company settings. Please try again.');
        }
    }
}
