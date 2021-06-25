@extends('layouts.app', ['activePage' => 'userSummary', 'titlePage' => __('User Summary')])
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


</head>
@section('content')



    <div class="content">
        <div class="container-fluid">
            <h3>User Summary for {{$user->first_name . " " . $user->last_name }}</h3>

            <div class="container-fluid">


                <ul class="nav nav-pills " id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-attendance-tab" data-toggle="pill" href="#attendanceTab" role="tab" aria-controls="attendanceTab" aria-selected="true">Attendance</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="pills-notes-tab" data-toggle="pill" href="#notesTab" role="tab" aria-controls="notesTab" aria-selected="false">Notes</a>
                    </li>

                    @if($role == 'TA')

                        <li class="nav-item">
                            <a class="nav-link" id="pills-interventionsTA-tab" data-toggle="pill" href="#interventionsTabTA" role="tab" aria-controls="interventionsTabTA" aria-selected="false">Interventions</a>
                        </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" id="pills-interventions-tab" data-toggle="pill" href="#interventionsTab" role="tab" aria-controls="interventionsTab" aria-selected="false">Interventions</a>
                    </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link" id="pills-git_analysis-tab" data-toggle="pill" href="#gitAnalysisTab" role="tab" aria-controls="gitAnalysisTab" aria-selected="true">Git Analysis</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="pills-buddy-check-tab" data-toggle="pill" href="#buddyCheckTab" role="tab" aria-controls="buddyCheckTab" aria-selected="true">Buddy Check</a>
                    </li>

                </ul>


                <div class="tab-content" id="pills-tabContent">
                    @include('user_summary/attendance_tab')
                    @include('user_summary/notes_tab')
                    @include('user_summary/interventions_tab')
                    @include('user_summary/interventions_TA_tab')
                    @include('user_summary/git_analysis_tab')
                    @include('user_summary/buddy_check_tab')
                </div>

            </div>
        </div>
    </div>

@endsection
