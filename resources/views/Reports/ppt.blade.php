<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $report->title }} - Presentation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .slide {
            min-height: 100vh;
            padding: 40px;
            position: relative;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: none;
        }
        .slide.active {
            display: block;
        }
        .slide-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .slide-title {
            font-size: 32px;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .slide-subtitle {
            font-size: 24px;
            color: #34495e;
        }
        .content {
            font-size: 20px;
            line-height: 1.6;
        }
        .footer {
            position: absolute;
            bottom: 20px;
            left: 40px;
            right: 40px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .navigation {
            position: fixed;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            z-index: 1000;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .nav-item {
            cursor: pointer;
            padding: 5px 10px;
            margin: 5px 0;
            color: #2c3e50;
            font-size: 14px;
        }
        .nav-item:hover {
            background: #2c3e50;
            color: white;
            border-radius: 3px;
        }
        .nav-item.active {
            background: #2c3e50;
            color: white;
            border-radius: 3px;
        }
        .slide-controls {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            display: flex;
            gap: 10px;
        }
        .control-btn {
            padding: 10px 20px;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .control-btn:hover {
            background: #34495e;
        }
        .risk-heat-map {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin: 20px 0;
        }
        .risk-cell {
            padding: 20px;
            text-align: center;
            color: white;
            border-radius: 5px;
        }
        .high { background-color: #e74c3c; }
        .medium { background-color: #f39c12; }
        .low { background-color: #27ae60; }
    </style>
</head>
<body>
    <!-- Navigation Menu -->
    <div class="navigation">
        <div class="nav-item" data-slide="0">Title</div>
        <div class="nav-item" data-slide="1">Report Metadata</div>
        <div class="nav-item" data-slide="2">Auditor Details</div>
        <div class="nav-item" data-slide="3">Dashboard Metrics</div>
        <div class="nav-item" data-slide="4">Table of Contents</div>
        <div class="nav-item" data-slide="5">Report Content</div>
        <div class="nav-item" data-slide="6">Risk Heat Map</div>
        <div class="nav-item" data-slide="7">Audit Checklist</div>
    </div>

    <!-- Slide Controls -->
    <div class="slide-controls">
        <button class="control-btn" id="prevSlide">Previous</button>
        <button class="control-btn" id="nextSlide">Next</button>
    </div>

    <!-- Title Slide -->
    <div class="slide active" id="slide-0">
        <div class="slide-header">
            <div class="slide-title">{{ $report->title }}</div>
            <div class="slide-subtitle">Audit Report</div>
        </div>
        <div class="content" style="text-align: center; margin-top: 100px;">
            <p>Date: {{ $report->date }}</p>
            <p>Version: {{ $report->version }}</p>
        </div>
        <div class="footer">
            {{ config('app.name') }}
        </div>
    </div>

    <!-- Report Metadata Slide -->
    <div class="slide" id="slide-1">
        <div class="slide-header">
            <div class="slide-title">Report Metadata</div>
        </div>
        <div class="content">
            
            <p><strong>Report Title:</strong> {{ $report->title }}</p>
            <p><strong>Report Version:</strong> {{ $report->version }}</p>
            <p><strong>Report Date:</strong> {{ $report->date }}</p>
            <p><strong>Classification:</strong> {{ $report->classification }}</p>
        </div>
    </div>

    <!-- Auditor Details Slide -->
    <div class="slide" id="slide-2">
        <div class="slide-header">
            <div class="slide-title">Auditor Details</div>
        </div>
        <div class="content">
            <p><strong>Auditor Name:</strong> {{ $report->auditor_name }}</p>
            <p><strong>Auditor Certification:</strong> {{ $report->auditor_certification }}</p>
        </div>
    </div>

    <!-- Dashboard Metrics Slide -->
    <div class="slide" id="slide-3">
        <div class="slide-header">
            <div class="slide-title">Dashboard Metrics</div>
        </div>
        <div class="content">
            <p><strong>Total Risks:</strong> {{ $report->total_risks }}</p>
            <p><strong>Critical Risks:</strong> {{ $report->critical_risks }}</p>
            <p><strong>Compliance Status:</strong> {{ $report->compliance_status }}%</p>
        </div>
    </div>

    <!-- Table of Contents Slide -->
    <div class="slide" id="slide-4">
        <div class="slide-header">
            <div class="slide-title">Table of Contents</div>
        </div>
        <div class="content">
            <ol>
                <li>Purpose & Use of the Report</li>
                <li>Background</li>
                <li>Audit Scope</li>
                <li>Auditor Independence</li>
                <li>Assessment Timings & Activities</li>
                <li>Audit Exclusions</li>
                <li>Sources of Information</li>
                <li>Limitations and Disclaimer</li>
                <li>Executive Summary</li>
                <li>Key Findings</li>
                <li>Key Recommendations</li>
            </ol>
        </div>
    </div>

    <!-- Report Content Slide -->
    <div class="slide" id="slide-5">
        <div class="slide-header">
            <div class="slide-title">Report Content</div>
        </div>
        <div class="content">
            <h3>Purpose & Use of the Report</h3>
            <p>{{ $report->purpose }}</p>

            <h3>Background</h3>
            <p>{{ $report->background }}</p>

            <h3>Audit Scope</h3>
            <p>{{ $report->audit_scope }}</p>

            <h3>Auditor Independence</h3>
            <p>{{ $report->auditor_independence }}</p>

            <h3>Assessment Timings & Activities</h3>
            <p>{{ $report->assessment_timings }}</p>

            <h3>Audit Exclusions</h3>
            <p>{{ $report->audit_exclusions }}</p>

            <h3>Sources of Information</h3>
            <p>{{ $report->information_sources }}</p>

            <h3>Limitations and Disclaimer</h3>
            <p>{{ $report->limitations }}</p>

            <h3>Executive Summary</h3>
            <p>{{ $report->executive_summary }}</p>

            <h3>Key Findings</h3>
            <p>{{ $report->key_findings }}</p>

            <h3>Key Recommendations</h3>
            <p>{{ $report->key_recommendations }}</p>
        </div>
    </div>

    <!-- Risk Heat Map Slide -->
    <div class="slide" id="slide-6">
        <div class="slide-header">
            <div class="slide-title">Risk Heat Map</div>
        </div>
        <div class="content">
            <div class="risk-heat-map">
                <div class="risk-cell high">High Impact<br>High Likelihood</div>
                <div class="risk-cell high">High Impact<br>Medium Likelihood</div>
                <div class="risk-cell medium">High Impact<br>Low Likelihood</div>
                <div class="risk-cell high">Medium Impact<br>High Likelihood</div>
                <div class="risk-cell medium">Medium Impact<br>Medium Likelihood</div>
                <div class="risk-cell low">Medium Impact<br>Low Likelihood</div>
                <div class="risk-cell medium">Low Impact<br>High Likelihood</div>
                <div class="risk-cell low">Low Impact<br>Medium Likelihood</div>
                <div class="risk-cell low">Low Impact<br>Low Likelihood</div>
            </div>
        </div>
    </div>

    <!-- Audit Checklist Slide -->
    <div class="slide" id="slide-7">
        <div class="slide-header">
            <div class="slide-title">Audit Checklist</div>
        </div>
        <div class="content">
            @if(isset($report->audit_checklist))
                {!! $report->audit_checklist !!}
            @else
                <p>No audit checklist available.</p>
            @endif
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const navItems = document.querySelectorAll('.nav-item');
        const prevBtn = document.getElementById('prevSlide');
        const nextBtn = document.getElementById('nextSlide');

        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            navItems.forEach(item => item.classList.remove('active'));
            
            slides[index].classList.add('active');
            navItems[index].classList.add('active');
            
            // Update button states
            prevBtn.disabled = index === 0;
            nextBtn.disabled = index === slides.length - 1;
            
            // Update URL hash
            window.location.hash = `#slide-${index}`;
        }

        // Navigation click handlers
        navItems.forEach(item => {
            item.addEventListener('click', function() {
                currentSlide = parseInt(this.getAttribute('data-slide'));
                showSlide(currentSlide);
            });
        });

        // Previous/Next button handlers
        prevBtn.addEventListener('click', () => {
            if (currentSlide > 0) {
                currentSlide--;
                showSlide(currentSlide);
            }
        });

        nextBtn.addEventListener('click', () => {
            if (currentSlide < slides.length - 1) {
                currentSlide++;
                showSlide(currentSlide);
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft' && currentSlide > 0) {
                currentSlide--;
                showSlide(currentSlide);
            } else if (e.key === 'ArrowRight' && currentSlide < slides.length - 1) {
                currentSlide++;
                showSlide(currentSlide);
            }
        });

        // Handle direct navigation via URL hash
        if (window.location.hash) {
            const slideIndex = parseInt(window.location.hash.replace('#slide-', ''));
            if (!isNaN(slideIndex) && slideIndex >= 0 && slideIndex < slides.length) {
                currentSlide = slideIndex;
                showSlide(currentSlide);
            }
        }

        // Initialize first slide
        showSlide(currentSlide);
    });
    </script>
</body>
</html>
