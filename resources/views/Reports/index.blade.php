@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Reports</h1>
        <a href="{{ route('reports.create') }}" class="btn btn-primary">Create New Report</a>
    </div>

    @if($reports->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                        <tr>
                            <td>{{ $report->title }}</td>
                            <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('download.pdf') }}" class="btn btn-sm btn-primary">PDF</a>
                                <a href="{{ route('download.ppt') }}" class="btn btn-sm btn-success">PPT</a>
                                <a href="{{ route('download.word') }}" class="btn btn-sm btn-info">Word</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>No reports found. Create your first report!</p>
    @endif
</div>
@endsection
