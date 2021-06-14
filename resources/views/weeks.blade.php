@extends('layouts.app', ['activePage' => 'group', 'titlePage' => __('Weeks')])
<?php
    #ddd($gitanalyses);
    $maxWeekNumber = 0;
    $index = 0;
    $latestGitAnalyses = -1;
/**
 * This method goes through the gitanalyses if there are any and searches for the last week uploaded in that group.
 */
foreach ($gitanalyses as $git) {
    if ($git->week_number >= $maxWeekNumber) {
        $maxWeekNumber = $git->week_number;
        $latestGitAnalyses = $index;
    }
    $index++;
}
/**
 * This if goes through the gitanalysis found in the previous for is good or doesn't have any gitanalyses
 * otherwise it initiates the datapoints with dummy data
 */
if ($latestGitAnalyses != -1) {
    $names = json_decode($gitanalyses[$latestGitAnalyses]->names);
    $emails = json_decode($gitanalyses[$latestGitAnalyses]->emails);
    $blame = json_decode($gitanalyses[$latestGitAnalyses]->blame);

    $dataPoints = array_fill(0, count($emails), null);
    $count = 0;
    foreach ($emails as $email) {
        $dataPoints[$count] = array("label"=>"$email", "y"=>$blame[$count]->percentage_in_comments);
        $count++;
    }
} else {
    $dataPoints = array(
        array("label"=>"Chrome", "y"=>64.02),
        array("label"=>"Firefox", "y"=>12.55),
        array("label"=>"IE", "y"=>8.47),
        array("label"=>"Safari", "y"=>6.08),
        array("label"=>"Edge", "y"=>4.29),
        array("label"=>"Others", "y"=>4.59)
    );
}
?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

@section('content')
    @php
    $user =auth()->user();
    $userId = $user->id;
    $role = \App\Models\CourseEditionUser::where('user_id', '=', $userId)->where('course_edition_id', '=', $edition_id)->first()->role;
    @endphp

    @if ($role == "lecturer" || $role == "HeadTA")
    <div class="content">
    <head>
        <script>
            window.onload = function() {

                var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    title: {
                        text: "Gitinspector"
                    },
                    subtitles: [{
                        text: "Latest week"
                    }],
                    data: [{
                        type: "pie",
                        yValueFormatString: "#,##0.00\"%\"",
                        indexLabel: "{label} ({y})",
                        dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chart.render();

            }
        </script>
    </head>

    <div class="content" style="display: flex">
        <div class="container-fluid">
            <button type="submit" name="update" class="btn btn-dark rounded-pill" onclick="window.location='{{ route('groups', ['edition_id'=>$edition_id]) }}'">Back!</button>

            <div class="row">
                @for($w=1; $w<=10; $w++)
                <div class="col-lg-1 col-md-3 col-sm-6">
                            <div class="card card-stats" style="width: 120px;">
                            <div class="card-icon">
                                <a class="nav-link" href="{{ route('week', [$group_id, $w]) }}">
                                    <p>Week {{ $w }}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

            <div class="container-fluid">
                @if(DB::table('gitanalyses')->where('group_id', "=", $group_id)->exists())
                    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
                @endif
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title">Students</h4>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="profile">
                                        <table class="table">
                                            <thead class="text-primary">
                                            <th>Netid</th>
                                            <th>Last Name</th>
                                            <th>First Name</th>
                                            <th>Email</th>
                                            </thead>
                                            <tbody>
                                            @foreach($users as $user)
                                                @if(DB::table('course_edition_user')->where("user_id","=", $user->user_id)->where('role','=','student')->exists())
                                                    <tr>
                                                        <td>
                                                            {{DB::table('users')
                                                                    ->where('id', '=', $user->user_id)
                                                                    ->value('net_id')}}
                                                        </td>
                                                        <td>
                                                            {{DB::table('users')
                                                                    ->where('id', '=', $user->user_id)
                                                                    ->value('last_name')}}
                                                        </td>
                                                        <td>
                                                            {{DB::table('users')
                                                                    ->where('id', '=', $user->user_id)
                                                                    ->value('first_name')}}
                                                        </td>
                                                        <td>
                                                            {{DB::table('users')
                                                                    ->where('id', '=', $user->user_id)
                                                                    ->value('email')}}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row"  >
                <div class="col-lg-6 col-md-12">
                    <div class="card" >
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Problem cases without interventions</h4>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="profile">
                                    <table class="table" style="table-layout:fixed;">
                                        <thead class="text-primary">
                                        <th>Name</th>
                                        <th style="width:10%">Week</th>
                                        <th style="width:40%">Note</th>
                                        <th style="width:10%">Signal</th>
                                        <th></th>
                                        </thead>
                                        <tbody>

                                        <!--
                                         Fetching the individual interventions and notes of the current group.
                                         Also, a list with the interventions that are strictly related to
                                         notes (that have reason of the format 'note1234') is needed,
                                         used to get the list of problematic notes that do not have interventions assigned.
                                        -->
                                        @php
                                            $notes = App\Models\Note::where('group_id', '=', $group->id)->orderBy('week')->get();
                                        $notesGood = [];
                                        foreach($notes as $note) {
                                            array_push($notesGood, $note);
                                        }
                                        $interventions = \App\Models\Intervention::where('group_id','=', $group->id)->get();
                                        $interventionNotes = [];
                                        foreach($interventions as $intervention) {
                                            if(preg_match("/^(note)\d+$/i", $intervention->reason)) {
                                                $note = App\Models\Note::find(preg_replace('/[^0-9]/', '', $intervention->reason));
                                                array_push($interventionNotes, $note);
                                            }
                                        }

                                        $notesNoInterventions = array_diff($notesGood, $interventionNotes);
                                        @endphp

                                        @foreach($notesNoInterventions as $note)
                                            @if($note->problem_signal >= 2)
                                                <tr>
                                                    <td>
                                                        {{$note->user->first_name . ' ' . $note->user->last_name}}
                                                    </td>
                                                    <td>
                                                        {{$note->week}}
                                                    </td>
                                                   <td >
                                                       <div style="overflow-x: hidden; overflow-y:auto;
                                                                   text-overflow: clip;
                                                                   display: -webkit-box;
                                                                   -webkit-line-clamp: 5; /* number of lines to show */
                                                                   -webkit-box-orient: vertical;">
                                                        {{$note->note}}
                                                            </div>
                                                    </td>
                                                    <td>
                                                        @if($note->problem_signal == 2)
                                                            <button title="Warning!" class="btn btn-squared-default btn-warning">
                                                                <br />
                                                                <br />
                                                            </button>
                                                        @else
                                                            <button title="Problematic!"  class="btn btn-squared-default btn-danger">
                                                                <br />
                                                                <br />
                                                            </button>
                                                        @endif

                                                    </td>
                                                    <td>
                                                        @include ('/interventions/intervention_create_note_based_modal')
                                                        <button class="btn btn-primary btn-sm rounded-0" type="button" name="update"  data-toggle="modal" data-target="{{"#createInterventionNote" . $note->id}}">
                                                            <span>Create</span>
                                                            <br>
                                                            <span>Intervention</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include ('/interventions/weeks_interventions_subview')




            </div>
        </div>
    </div>
    @endif

    @if ($role == "TA")
        <div class="content">
            <div class="container-fluid">
                <button type="submit" name="update" class="btn btn-dark rounded-pill" onclick="window.location='{{ route('groups', ['edition_id'=>$edition_id]) }}'">Back!</button>

                <div class="row">
                    @for($w=1; $w<=10; $w++)
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-stats" style="width: 120px;">
                                <div class="card-icon">
                                    <a class="nav-link" href="{{ route('week', [$group_id, $w]) }}">
                                        <p>Week {{ $w }}</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
                <div class="row"  >
                    <div class="col-lg-6 col-md-12">
                        <div class="card" >
                            <div class="card-header card-header-primary">
                                <h4 class="card-title">Problem cases</h4>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="profile">
                                        <table class="table" style="table-layout:fixed;">
                                            <thead class="text-primary">
                                            <th style="width:35%">Name</th>
                                            <th style="width:10%">Week</th>
                                            <th style="width:35%">Note</th>

                                            <th style="width:20%">Signal</th>
                                            </thead>
                                            <tbody>

                                            <!--
                                             Fetching the group interventions and notes of the current group.
                                             Also, a list with the interventions that are strictly related to
                                             notes (that have reason of the format 'note1234') is needed,
                                             used to get the list of problematic notes that do not have interventions assigned.
                                            -->

                                        @php
                                            $notes = App\Models\Note::where('group_id', '=', $group->id)->orderBy('week')->get();
                                        $notesGood = [];
                                        foreach($notes as $note) {
                                            array_push($notesGood, $note);
                                        }
                                        $interventions = \App\Models\Intervention::where('group_id','=', $group->id)->get();
                                        $interventionNotes = [];
                                        foreach($interventions as $intervention) {
                                            if(preg_match("/^(note)\d+$/i", $intervention->reason)) {
                                                $note = App\Models\Note::find(preg_replace('/[^0-9]/', '', $intervention->reason));
                                                array_push($interventionNotes, $note);
                                            }
                                        }

                                        $notesNoInterventions = array_diff($notesGood, $interventionNotes);
                                        @endphp

                                            @foreach($notesNoInterventions as $note)
                                                @if($note->problem_signal >= 2)
                                                    <tr>
                                                        <td>
                                                            {{$note->user->first_name . ' ' . $note->user->last_name}}
                                                        </td>
                                                        <td>
                                                            {{$note->week}}
                                                        </td>
                                                        <td >
                                                            <div style="overflow-x: hidden; overflow-y:auto;
                                                                   text-overflow: clip;
                                                                   display: -webkit-box;
                                                                   -webkit-line-clamp: 5; /* number of lines to show */
                                                                   -webkit-box-orient: vertical;">
                                                                {{$note->note}}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if($note->problem_signal == 2)
                                                                <button title="Warning!" class="btn btn-squared-default btn-warning">
                                                                    <br />
                                                                    <br />
                                                                </button>
                                                            @else
                                                                <button title="Problematic!"  class="btn btn-squared-default btn-danger">
                                                                    <br />
                                                                    <br />
                                                                </button>
                                                            @endif

                                                        </td>

                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                                        @foreach($notesNoInterventions as $note)
                                            @php

                                            @endphp
                                            @if($note->problem_signal >= 2)
                                                <tr>
                                                    <td>
                                                        {{$note->user->first_name . ' ' . $note->user->last_name}}
                                                    </td>
                                                    <td>
                                                        {{$note->week}}
                                                    </td>
                                                    <td>
                                                       <div style=" overflow-x: hidden; overflow-y:auto;
                                                        text-overflow: clip;
                                                        display: -webkit-box;
                                                        -webkit-line-clamp: 5; /* number of lines to show */
                                                        -webkit-box-orient: vertical;">
                                                        {{$note->note}}
                                                       </div>
                                                    </td>
                                                    <td>
                                                        @if($note->problem_signal == 2)
                                                            <button title="Warning!" class="btn btn-squared-default btn-warning">
                                                                <br />
                                                                <br />
                                                            </button>
                                                        @else
                                                            <button title="Problematic!"  class="btn btn-squared-default btn-danger">
                                                                <br />
                                                                <br />
                                                            </button>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @include ('/interventions/intervention_create_note_based_modal')
                                                        <button class="btn btn-primary btn-sm rounded-0" type="button" name="update"  data-toggle="modal" data-target="{{"#createInterventionNote" . $note->id}}">
                                                            <span>Create</span>
                                                            <br>
                                                            <span>Intervention</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    @include ('/interventions/weeks_interventions_subview')
                </div>
            </div>

        </div>

    @endif



@endsection
