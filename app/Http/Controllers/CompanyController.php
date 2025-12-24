<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company = Company::first();
        return response()->json($company);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'company_name_en' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'branch_name' => 'nullable|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'address_la' => 'nullable|string|max:255',
            'address_en' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'facebook_page' => 'nullable|string|max:255',
            'whatsapp_telegram' => 'nullable|string|max:255',
            'tax_id' => 'nullable|string|max:50',
            'register_no' => 'nullable|string|max:50',
            'established_date' => 'nullable|date',
            'business_type' => 'nullable|string|max:100',
            'capital' => 'nullable|numeric',
            'ceo_name' => 'nullable|string|max:255',
            'ceo_phone' => 'nullable|string|max:20',
            'ceo_email' => 'nullable|email|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'timezone' => 'nullable|string|max:50',
            'currency' => 'nullable|string|max:3',
            'language_default' => 'nullable|string|max:2',
            'invoice_prefix' => 'nullable|string|max:10',
            'payroll_prefix' => 'nullable|string|max:10',
            'print_settings' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('company_logos', 'public');
            $data['logo'] = $logoPath;
        }

        $company = Company::create($data);

        return response()->json([
            'message' => 'Company information saved successfully',
            'company' => $company
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'company_name_en' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'branch_name' => 'nullable|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'address_la' => 'nullable|string|max:255',
            'address_en' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'facebook_page' => 'nullable|string|max:255',
            'whatsapp_telegram' => 'nullable|string|max:255',
            'tax_id' => 'nullable|string|max:50',
            'register_no' => 'nullable|string|max:50',
            'established_date' => 'nullable|date',
            'business_type' => 'nullable|string|max:100',
            'capital' => 'nullable|numeric',
            'ceo_name' => 'nullable|string|max:255',
            'ceo_phone' => 'nullable|string|max:20',
            'ceo_email' => 'nullable|email|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'timezone' => 'nullable|string|max:50',
            'currency' => 'nullable|string|max:3',
            'language_default' => 'nullable|string|max:2',
            'invoice_prefix' => 'nullable|string|max:10',
            'payroll_prefix' => 'nullable|string|max:10',
            'print_settings' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            
            $logoPath = $request->file('logo')->store('company_logos', 'public');
            $data['logo'] = $logoPath;
        }

        $company->update($data);

        return response()->json([
            'message' => 'Company information updated successfully',
            'company' => $company
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        // Delete logo if exists
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }
        
        $company->delete();

        return response()->json(['message' => 'Company information deleted successfully']);
    }
}