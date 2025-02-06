@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Report Generator</h1>
    <div id="alertContainer"></div>
    
    <form id="reportForm" method="POST" action="{{ route('reports.store') }}">
        @csrf
        <!-- Report Metadata -->
        <div class="section-header">
            <i class="fas fa-info-circle me-2"></i><h2>Report Metadata</h2>
        </div>
        <div class="form-group">
            <label for="reportTitle">Report Title:</label>
            <input type="text" id="reportTitle" name="title" placeholder="Enter report title" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="reportVersion">Report Version:</label>
            <input type="text" id="reportVersion" name="version" placeholder="Enter report version" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="reportDate">Report Date:</label>
            <input type="date" id="reportDate" name="date" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="reportClassification">Classification:</label>
            <select id="reportClassification" name="classification" placeholder="Enter classification" class="form-control" required>
                <option value="Private">Private</option>
                <option value="Internal">Internal</option>
                <option value="Public">Public</option>
            </select>
        </div>

        <!-- Auditor Details -->
        <div class="section-header">
            <i class="fas fa-user-tie me-2"></i><h2>Auditor Details</h2>
        </div>
        <div class="form-group">
            <label for="auditorName">Auditor Name:</label>
            <input type="text" id="auditorName" name="auditor_name" placeholder="Enter auditor name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="auditorCertification">Auditor Certification:</label>
            <input type="text" id="auditorCertification" name="auditor_certification" placeholder="Enter auditor certification" class="form-control" required>
        </div>

        <!-- Dashboard Metrics -->
        <div class="section-header">
            <i class="fas fa-chart-line me-2"></i><h2>Dashboard Metrics</h2>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="totalRisks">Total Risks:</label>
                    <input type="number" id="totalRisks" name="total_risks" placeholder="Enter total risks" class="form-control" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="criticalRisks">Critical Risks:</label>
                    <input type="number" id="criticalRisks" name="critical_risks" placeholder="Enter critical risks" class="form-control" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="complianceStatus">Compliance Status (%):</label>
                    <input type="number" id="complianceStatus" name="compliance_status" placeholder="Enter compliance status" class="form-control" required>              
                </div>
            </div>
        </div>

        <!-- Report Content -->
        <div class="section-header">
            <i class="fas fa-file-alt me-2"></i><h2>Table of Content</h2>
        </div>
        <div class="form-group">
            <label for="tocSections">Sections (Comma Separated):</label>
            <textarea id="tocSections" name="toc_sections" placeholder="Enter sections (comma-separated)" class="form-control"></textarea>
        </div>

        <div class="section-header">
            <i class="fas fa-file-alt me-2"></i><h2>Report Content</h2>
        </div>

        <div class="form-group">
            <label for="purpose">Purpose & Use of the Report:</label>
            <textarea id="purpose" name="purpose" placeholder="Enter purpose" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="background">Background:</label>
            <textarea id="background" name="background" placeholder="Enter background" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="auditScope">Audit Scope:</label>
            <textarea id="auditScope" name="audit_scope" placeholder="Enter audit scope" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="auditorIndependence">Auditor Independence:</label>
            <textarea id="auditorIndependence" name="auditor_independence" placeholder="Enter auditor independence" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="assessmentTimings">Assessment Timings & Activities:</label>
            <textarea id="assessmentTimings" name="assessment_timings" placeholder="Enter assessment timings and activities" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="auditExclusions">Audit Exclusions:</label>
            <textarea id="auditExclusions" name="audit_exclusions" placeholder="Enter audit exclusions" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="sourcesOfInformation">Sources of Information:</label>
            <textarea id="sourcesOfInformation" name="sources_of_information" placeholder="Enter sources of information" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="limitations">Limitations and Disclaimer:</label>
            <textarea id="limitations" name="limitations" placeholder="Enter limitations and disclaimer" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="executiveSummary">Executive Summary:</label>
            <textarea id="executiveSummary" name="executive_summary" placeholder="Enter executive summary" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="keyFindings">Key Findings:</label>
            <textarea id="keyFindings" name="key_findings" placeholder="Enter key findings" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="keyRecommendations">Key Recommendations:</label>
            <textarea id="keyRecommendations" name="key_recommendations" placeholder="Enter key recommendations" class="form-control"></textarea>
        </div>

        <!-- Report Options -->
        <div class="section-header">
            <i class="fas fa-cog me-2"></i><h2>Risk Heat Map</h2>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Include Risk Heat Map in Report:</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="risk_heat_map" value="yes" checked>
                            <span>Yes</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="risk_heat_map" value="no">
                            <span>No</span>
                        </label>
                    </div>
                </div>

                <div class="section-header">
            <i class="fas fa-cog me-2"></i><h2>Audit Checklist</h2>
        </div>
                <div class="form-group">
                    <label>Include Audit Checklist in Report:</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="audit_checklist" value="yes" checked>
                            <span>Yes</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="audit_checklist" value="no">
                            <span>No</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="section-header">
            <i class="fas fa-cog me-2"></i><h2>Export Options</h2>
        </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Enable Export to PDF:</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="export_pdf" value="yes" checked>
                            <span>Yes</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="export_pdf" value="no">
                            <span>No</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Enable Export to PPT:</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="export_ppt" value="yes" checked>
                            <span>Yes</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="export_ppt" value="no">
                            <span>No</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Enable Export to Word:</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="export_word" value="yes" checked>
                            <span>Yes</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="export_word" value="no">
                            <span>No</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Format Selection -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Select Report Formats</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="generate_pdf" value="yes" id="generatePdf" checked>
                            <label class="form-check-label" for="generatePdf">
                                Generate PDF Report
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="generate_ppt" value="yes" id="generatePpt">
                            <label class="form-check-label" for="generatePpt">
                                Generate PowerPoint Presentation
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="generate_word" value="yes" id="generateWord">
                            <label class="form-check-label" for="generateWord">
                                Generate Word Document
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <button type="button" onclick="submitReportForm()" class="btn btn-primary">
                    <i class="fas fa-file-export me-2"></i>Generate Reports
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function validateForm() {
    const form = document.getElementById('reportForm');
    let isValid = true;
    
    // Clear previous validation states
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.getElementById('alertContainer').innerHTML = '';
    
    // Required fields validation
    const requiredFields = {
        'title': 'Report Title',
        'version': 'Report Version',
        'date': 'Report Date',
        'classification': 'Classification',
        'auditor_name': 'Auditor Name',
        'auditor_certification': 'Auditor Certification',
        'total_risks': 'Total Risks',
        'critical_risks': 'Critical Risks',
        'compliance_status': 'Compliance Status',
        'toc_sections': 'Table of Content',
        'purpose': 'Purpose & Use of the Report',
        'background': 'Background',
        'audit_scope': 'Audit Scope',
        'auditor_independence': 'Auditor Independence',
        'assessment_timings': 'Assessment Timings & Activities',
        'audit_exclusions': 'Audit Exclusions',
        'sources_of_information': 'Sources of Information',
        'limitations': 'Limitations and Disclaimer',
        'executive_summary': 'Executive Summary',
        'key_findings': 'Key Findings',
        'key_recommendations': 'Key Recommendations'
    };
    
    const errors = [];
    
    Object.entries(requiredFields).forEach(([field, label]) => {
        const element = form.querySelector(`[name="${field}"]`);
        if (!element || !element.value.trim()) {
            if (element) {
                element.classList.add('is-invalid');
            }
            errors.push(`${label} is required`);
            isValid = false;
        }
    });
    
    // Date validation
    const startDateInput = form.querySelector('[name="start_date"]');
    const endDateInput = form.querySelector('[name="end_date"]');
    
    if (startDateInput && endDateInput) {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        if (startDate && endDate && startDate >= endDate) {
            endDateInput.classList.add('is-invalid');
            errors.push('End Date must be after Start Date');
            isValid = false;
        }
    }
    
    // Format selection validation
    const generatePdf = form.querySelector('[name="generate_pdf"]');
    const generatePpt = form.querySelector('[name="generate_ppt"]');
    const generateWord = form.querySelector('[name="generate_word"]');
    
    if (!generatePdf?.checked && !generatePpt?.checked && !generateWord?.checked) {
        errors.push('Please select at least one report format');
        isValid = false;
    }
    
    // Display validation errors if any
    if (errors.length > 0) {
        document.getElementById('alertContainer').innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    ${errors.map(error => `<li>${error}</li>`).join('')}
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
    }
    
    return isValid;
}

function submitReportForm() {
    if (!validateForm()) {
        return;
    }
    
    const form = document.getElementById('reportForm');
    const formData = new FormData(form);
    
    // Show loading state
    const button = form.querySelector('button[type="button"]');
    const originalButtonText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Generating Reports...';
    
    // Clear previous alerts
    document.getElementById('alertContainer').innerHTML = '';
    
    // Get CSRF token from meta tag or form input
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                     document.querySelector('input[name="_token"]')?.value;
    
    fetch('{{ route('reports.store') }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json().then(data => ({
        ok: response.ok,
        status: response.status,
        data: data
    })))
    .then(({ ok, status, data }) => {
        if (!ok) {
            throw { status, ...data };
        }
        
        // Show success message
        document.getElementById('alertContainer').innerHTML = `
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
        
        // Handle downloads
        if (data.downloads && data.downloads.length > 0) {
            data.downloads.forEach((url, index) => {
                setTimeout(() => {
                    const link = document.createElement('a');
                    link.href = url;
                    link.style.display = 'none';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }, index * 1000);
            });
        }
        
        // Reset form
        form.reset();
        
        // Reset date inputs and PDF checkbox
        const today = new Date().toISOString().split('T')[0];
        const startDateInput = form.querySelector('[name="start_date"]');
        const endDateInput = form.querySelector('[name="end_date"]');
        const pdfCheckbox = form.querySelector('[name="generate_pdf"]');
        
        if (startDateInput) startDateInput.value = today;
        if (endDateInput) endDateInput.value = today;
        if (pdfCheckbox) pdfCheckbox.checked = true;
    })
    .catch(error => {
        console.error('Error:', error);
        
        let errorMessage = 'An error occurred while generating the reports.';
        if (error.errors) {
            // Handle Laravel validation errors
            errorMessage = Object.values(error.errors).flat().join('<br>');
        } else if (error.message) {
            errorMessage = error.message;
        }
        
        document.getElementById('alertContainer').innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                ${errorMessage}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
    })
    .finally(() => {
        // Reset button state
        button.disabled = false;
        button.innerHTML = originalButtonText;
    });
}

// Initialize date inputs with today's date
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const startDateInput = document.querySelector('[name="start_date"]');
    const endDateInput = document.querySelector('[name="end_date"]');
    
    if (startDateInput) startDateInput.value = today;
    if (endDateInput) endDateInput.value = today;
});
</script>
@endsection
