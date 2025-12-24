<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'company_name',
        'company_name_en',
        'logo',
        'branch_name',
        'slogan',
        'address_la',
        'address_en',
        'village',
        'district',
        'province',
        'postal_code',
        'phone',
        'telephone',
        'email',
        'website',
        'facebook_page',
        'whatsapp_telegram',
        'tax_id',
        'register_no',
        'established_date',
        'business_type',
        'capital',
        'ceo_name',
        'ceo_phone',
        'ceo_email',
        'bank_name',
        'bank_account_name',
        'bank_account_number',
        'timezone',
        'currency',
        'language_default',
        'invoice_prefix',
        'payroll_prefix',
        'print_settings'
    ];

    protected $casts = [
        'established_date' => 'date',
        'capital' => 'decimal:2'
    ];
}