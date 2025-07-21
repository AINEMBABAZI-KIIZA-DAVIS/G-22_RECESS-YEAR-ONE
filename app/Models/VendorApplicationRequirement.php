<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorApplicationRequirement extends Model
{
    use HasFactory;

    protected $table = 'vendor_applications_requirements';

    protected $fillable = [
        'name',
        'category',
        'type',
        'weight',
        'required',
        'status',
    ];

    // For future: requirements can be linked to many applications
    public function applications()
    {
        return $this->belongsToMany(VendorApplication::class, 'vendor_application_requirement_application', 'requirement_id', 'application_id');
    }
} 