<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'contact_email',
        'annual_revenue_pdf',
        'regulatory_pdf',
        'reputation_pdf',
        'status',
        'validation_results',
        'scheduled_visit_at',
    ];

    protected $casts = [
        'scheduled_visit_at' => 'datetime',
        'validation_results' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requirements()
    {
        return $this->belongsToMany(VendorApplicationRequirement::class, 'vendor_application_requirement_application', 'application_id', 'requirement_id');
    }
}