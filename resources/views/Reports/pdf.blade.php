@extends('layouts.app')

@section('content')
<style>
    body {
        counter-reset: page;
    }
    .page {
        position: relative;
        padding: 20px;
        margin-bottom: 20px;
    }
    .toc {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 5px;
        margin: 20px auto;
        max-width: 800px;
    }
    .toc-item {
        color: #007bff;
        cursor: pointer;
        margin: 8px 0;
        text-decoration: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .toc-item:hover {
        text-decoration: underline;
    }
    .toc-item .page-ref {
        color: #666;
        font-size: 0.9em;
    }
    .section {
        scroll-margin-top: 20px;
    }
    .first-page {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .index-title {
        text-align: center;
        margin-bottom: 30px;
    }
</style>

<div class="container">
    {{-- First Page: Index --}}
    <div class="index-page">
        <h1>Index</h1>
        <ul>
            <li><a href="#report-title">Report Title</a></li>
            <li><a href="#report-details">1. Report Details</a></li>
            <li><a href="#auditor-info">2. Auditor Information</a></li>
            <li><a href="#key-metrics">3. Key Metrics</a></li>
            <li><a href="#executive-summary">4. Executive Summary</a></li>
            <li><a href="#key-findings">5. Key Findings</a></li>
            <li><a href="#key-recommendations">6. Key Recommendations</a></li>
            <li><a href="#detailed-info">7. Detailed Information</a></li>
        </ul>
    </div>
    {{-- Start Report Content on Second Page --}}
    <div class="report-content" style="page-break-before: always;">
        <h1 id="report-title">{{ $report->title }}</h1>
    </div>
    {{-- Report Details --}}
    <div class="section" style="page-break-before: always;">
        <h2 id="report-details">1. Report Details</h2>
        <p><strong>Version:</strong> {{ $report->version }}</p>
        <p><strong>Date:</strong> {{ $report->date }}</p>
        <p><strong>Classification:</strong> {{ $report->classification }}</p>
    </div>
    {{-- Auditor Information --}}
    <div class="section" style="page-break-before: always;">
        <h2 id="auditor-info">2. Auditor Information</h2>
        <p><strong>Name:</strong> {{ $report->auditor_name }}</p>
        <p><strong>Certification:</strong> {{ $report->auditor_certification }}</p>
    </div>
    {{-- Key Metrics --}}
    <div class="section" style="page-break-before: always;">
        <h2 id="key-metrics">3. Key Metrics</h2>
        <p><strong>Total Risks:</strong> {{ $report->total_risks }}</p>
        <p><strong>Critical Risks:</strong> {{ $report->critical_risks }}</p>
        <p><strong>Compliance Status:</strong> {{ $report->compliance_status }}%</p>
    </div>
    {{-- Executive Summary --}}
    <div class="section" style="page-break-before: always;">
        <h2 id="executive-summary">4. Executive Summary</h2>
        <p>{{ $report->executive_summary }}</p>
    </div>
    {{-- Key Findings --}}
    <div class="section" style="page-break-before: always;">
        <h2 id="key-findings">5. Key Findings</h2>
        <p>{{ $report->key_findings }}</p>
    </div>
    {{-- Key Recommendations --}}
    <div class="section" style="page-break-before: always;">
        <h2 id="key-recommendations">6. Key Recommendations</h2>
        <p>{{ $report->key_recommendations }}</p>
    </div>
    {{-- Detailed Information --}}
    <div class="section" style="page-break-before: always;">
        <h2 id="detailed-info">7. Detailed Information</h2>
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toc-item').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
    </script>
</div>
@endsection
