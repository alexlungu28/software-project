@extends('layouts.app', ['class' => 'off-canvas-sidebar', 'activePage' => 'export', 'title' => __('Export')])

@section('content')

<body>
    <div class="container" style="text-align: center;  position: fixed; top: 25%; left: 50%; margin-top: -10px; margin-left: -20%;">
        <div class="card bg-light mt-3" style="color: black; font-size: 1.2rem;">
            <div class="card-header">
                Select which files you would like to export:
            </div>
            <div class="card-body">
                <a class="btn btn-info" href="{{ route('exportGrades', [$edition_id]) }}">Export Grades</a>
                <a class="btn btn-info" href="{{ route('exportGrades', [$edition_id]) }}">Export Group Interventions</a>
                <a class="btn btn-info" href="{{ route('exportGroupNotes', [$edition_id]) }}">Export Group Notes</a>
                <a class="btn btn-info" href="{{ route('exportGrades', [$edition_id]) }}">Export Individual Interventions</a>
                <a class="btn btn-info" href="{{ route('exportGrades', [$edition_id]) }}">Export Individual Notes</a>
                <a class="btn btn-info" href="{{ route('exportRubrics', [$edition_id]) }}">Export Rubrics</a>
                <a class="btn btn-info" href="{{ route('exportUserList', [$edition_id]) }}">Export User List</a>
            </div>
        </div>
    </div>
</body>
@endsection
