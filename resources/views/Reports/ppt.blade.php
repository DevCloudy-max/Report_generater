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
        }
        .slide {
            page-break-after: always;
            padding: 40px;
            position: relative;
            height: 600px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #34495e;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Title Slide -->
    <div class="slide">
        <div class="slide-header">
            <div class="slide-title">{{ $report->title }}</div>
            <div class="slide-subtitle">Project Report</div>
        </div>
        <div class="content" style="text-align: center; margin-top: 100px;">
            <p>{{ $report->project_name }}</p>
            <p>Client: {{ $report->client_name }}</p>
            <p>Date: {{ now()->format('F j, Y') }}</p>
        </div>
        <div class="footer">
            {{ config('app.name') }}
        </div>
    </div>

    <!-- Executive Summary Slide -->
    <div class="slide">
        <div class="slide-header">
            <div class="slide-title">Executive Summary</div>
        </div>
        <div class="content">
            {{ $report->executive_summary }}
        </div>
        <div class="footer">
            {{ $report->title }}
        </div>
    </div>

    <!-- Project Overview Slide -->
    <div class="slide">
        <div class="slide-header">
            <div class="slide-title">Project Overview</div>
        </div>
        <div class="content">
            <table>
                <tr>
                    <th>Project Name</th>
                    <td>{{ $report->project_name }}</td>
                </tr>
                <tr>
                    <th>Client Name</th>
                    <td>{{ $report->client_name }}</td>
                </tr>
                <tr>
                    <th>Duration</th>
                    <td>{{ $report->start_date }} to {{ $report->end_date }}</td>
                </tr>
                <tr>
                    <th>Project Manager</th>
                    <td>{{ $report->project_manager }}</td>
                </tr>
            </table>
        </div>
        <div class="footer">
            {{ $report->title }}
        </div>
    </div>

    <!-- Project Objectives Slide -->
    @if($report->project_objectives)
    <div class="slide">
        <div class="slide-header">
            <div class="slide-title">Project Objectives</div>
        </div>
        <div class="content">
            {{ $report->project_objectives }}
        </div>
        <div class="footer">
            {{ $report->title }}
        </div>
    </div>
    @endif

    <!-- Methodology Slide -->
    @if($report->methodology)
    <div class="slide">
        <div class="slide-header">
            <div class="slide-title">Methodology</div>
        </div>
        <div class="content">
            {{ $report->methodology }}
        </div>
        <div class="footer">
            {{ $report->title }}
        </div>
    </div>
    @endif

    <!-- Key Findings Slide -->
    @if($report->findings)
    <div class="slide">
        <div class="slide-header">
            <div class="slide-title">Key Findings</div>
        </div>
        <div class="content">
            {{ $report->findings }}
        </div>
        <div class="footer">
            {{ $report->title }}
        </div>
    </div>
    @endif

    <!-- Recommendations Slide -->
    @if($report->recommendations)
    <div class="slide">
        <div class="slide-header">
            <div class="slide-title">Recommendations</div>
        </div>
        <div class="content">
            {{ $report->recommendations }}
        </div>
        <div class="footer">
            {{ $report->title }}
        </div>
    </div>
    @endif

    <!-- Risk Heat Map Slide -->
    @if($report->risk_heat_map)
    <div class="slide">
        <div class="slide-header">
            <div class="slide-title">Risk Heat Map</div>
        </div>
        <div class="content">
            <!-- Risk heat map visualization would go here -->
            <p>Risk Assessment Overview:</p>
            <ul>
                <li>High-risk areas identified</li>
                <li>Risk mitigation strategies</li>
                <li>Impact analysis</li>
            </ul>
        </div>
        <div class="footer">
            {{ $report->title }}
        </div>
    </div>
    @endif

    <!-- Audit Checklist Slide -->
    @if($report->audit_checklist)
    <div class="slide">
        <div class="slide-header">
            <div class="slide-title">Audit Checklist</div>
        </div>
        <div class="content">
            <!-- Audit checklist details would go here -->
            <p>Compliance Overview:</p>
            <ul>
                <li>Audit findings</li>
                <li>Compliance status</li>
                <li>Action items</li>
            </ul>
        </div>
        <div class="footer">
            {{ $report->title }}
        </div>
    </div>
    @endif

    <!-- Thank You Slide -->
    <div class="slide">
        <div class="slide-header">
            <div class="slide-title">Thank You</div>
        </div>
        <div class="content" style="text-align: center; margin-top: 100px;">
            <p>For questions or additional information, please contact:</p>
            <p>{{ $report->project_manager }}</p>
            <p>Project Manager</p>
        </div>
        <div class="footer">
            {{ config('app.name') }} &copy; {{ date('Y') }}
        </div>
    </div>
</body>
</html>
