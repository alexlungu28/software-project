@extends('layouts.app', ['class' => 'off-canvas-sidebar', 'activePage' => 'export', 'title' => __('Export')])

@section('content')

<body>
    <div class="container" style="text-align: center;  position: fixed; top: 25%; left: 50%; margin-top: -50px; margin-left: -450px;">
        <div class="card bg-light mt-3" style="color: black; font-size: 1.2rem;">
            <div class="card-header">
                Select which files you would like to export:
            </div>
            <div class="card-body">
                <a class="btn btn-info" href="{{ route('exportUserList', [$edition_id]) }}">Export User List</a>
                <a class="btn btn-info" href="{{ route('exportGrades', [$edition_id]) }}">Export Grades</a>
                <a class="btn btn-info" href="{{ route('exportGrades', [$edition_id]) }}">Export Interventions</a>
                <a class="btn btn-info" href="{{ route('exportGrades', [$edition_id]) }}">Export Notes</a>
                <a class="btn btn-info" href="{{ route('exportGrades', [$edition_id]) }}">Export Rubrics</a>
            </div>
        </div>
    </div>
</body>
@endsection
