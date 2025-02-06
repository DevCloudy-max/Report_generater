@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $report->title }}</h1>

    <div style="margin: 20px 0;">
        <h2>Report Details</h2>
        <p><strong>Version:</strong> {{ $report->version }}</p>
        <p><strong>Date:</strong> {{ $report->date }}</p>
        <p><strong>Classification:</strong> {{ $report->classification }}</p>
    </div>

    <div style="margin: 20px 0;">
        <h2>Auditor Information</h2>
        <p><strong>Name:</strong> {{ $report->auditor_name }}</p>
        <p><strong>Certification:</strong> {{ $report->auditor_certification }}</p>
    </div>

    <div style="margin: 20px 0;">
        <h2>Key Metrics</h2>
        <p><strong>Total Risks:</strong> {{ $report->total_risks }}</p>
        <p><strong>Critical Risks:</strong> {{ $report->critical_risks }}</p>
        <p><strong>Compliance Status:</strong> {{ $report->compliance_status }}%</p>
    </div>

    <div style="margin: 20px 0;">
        <h2>Executive Summary</h2>
        <p>{{ $report->executive_summary }}</p>
    </div>

    <div style="margin: 20px 0;">
        <h2>Key Findings</h2>
        <p>{{ $report->key_findings }}</p>
    </div>

    <div style="margin: 20px 0;">
        <h2>Key Recommendations</h2>
        <p>{{ $report->key_recommendations }}</p>
    </div>

    <div style="margin: 20px 0;">
        <h2>Detailed Information</h2>
        <h3>Purpose</h3>
        <p>{{ $report->purpose }}</p>

        <h3>Background</h3>
        <p>{{ $report->background }}</p>

        <h3>Audit Scope</h3>
        <p>{{ $report->audit_scope }}</p>

        <h3>Assessment Timings</h3>
        <p>{{ $report->assessment_timings }}</p>

        <h3>Limitations</h3>
        <p>{{ $report->limitations }}</p>
    </div>
</div>
@endsection
