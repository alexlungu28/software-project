@extends('layouts.app', ['activePage' => 'group', 'titlePage' => __('Weeks')])
<?php

$dataPoints = array(
    array("label"=>"Chrome", "y"=>64.02),
    array("label"=>"Firefox", "y"=>12.55),
    array("label"=>"IE", "y"=>8.47),
    array("label"=>"Safari", "y"=>6.08),
    array("label"=>"Edge", "y"=>4.29),
    array("label"=>"Others", "y"=>4.59)
)

?>
@section('content')
    <head>
        <script>
            window.onload = function() {

                var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    title: {
                        text: "Usage Share of Desktop Browsers"
                    },
                    subtitles: [{
                        text: "November 2017"
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
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Problem cases</h4>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="profile">
                                    <table class="table">
                                        <thead class="text-primary">
                                        <th>Solved</th>
                                        <th>Week</th>
                                        <th>Regarding</th>
                                        <th>Content</th>
                                        <th>Problem Signal</th>
                                        </thead>
                                        <tbody>
                                        @foreach($group->groupnotes->sortBy('week') as $groupnote)
                                            @if($groupnote->problem_signal >= 2)
                                            <tr>
                                                <td>

                                                </td>
                                                <td>
                                                    {{$groupnote->week}}
                                                </td>
                                                <td>
                                                    Group
                                                </td>
                                                <td>
                                                    <textarea readonly=true style="width: 100%">{{$groupnote->note}}</textarea>
                                                </td>
                                                <td>
                                                    {{$groupnote->problem_signal}}
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                        @foreach($group->notes->sortBy('week') as $note)
                                            @if($note->problem_signal >= 2)
                                                <tr>
                                                    <td>

                                                    </td>
                                                    <td>
                                                        {{$note->week}}
                                                    </td>
                                                    <td>
                                                        {{$note->user->first_name . ' ' . $note->user->last_name}}
                                                    </td>
                                                    <td>
                                                        <textarea readonly=true style="width: 100%">{{$note->note}}</textarea>
                                                    </td>
                                                    <td>
                                                        {{$note->problem_signal}}
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
        <div class="container-fluid">
        @if(DB::table('gitanalyses')->where('group_id', "=", $group_id)->exists())
                <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
        @endif
            <div class="row">
                <div class="col-lg-12 col-md-12">
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
    </div>
@endsection
