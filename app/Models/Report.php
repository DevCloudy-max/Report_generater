<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'version',
        'date',
        'classification',
        'auditor_name',
        'auditor_certification',
        'total_risks',
        'critical_risks',
        'compliance_status',
        'toc_sections',
        'purpose',
        'background',
        'audit_scope',
        'auditor_independence',
        'assessment_timings',
        'audit_exclusions',
        'sources_of_information',
        'limitations',
        'executive_summary',
        'key_findings',
        'key_recommendations',
        'risk_heat_map',
        'audit_checklist'
    ];

    protected $casts = [
        'date' => 'date',
        'risk_heat_map' => 'boolean',
        'audit_checklist' => 'boolean',
        'total_risks' => 'integer',
        'critical_risks' => 'integer',
        'compliance_status' => 'float'
    ];
}
