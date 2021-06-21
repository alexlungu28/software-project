
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
                                            @foreach($users as $courseUser)
                                                @if(DB::table('course_edition_user')->where("user_id","=", $courseUser->user_id)->where('role','=','student')->exists())
                                                    <tr>
                                                        @php
                                                            $user = App\Models\User::find($courseUser->user_id);
                                                        @endphp
                                                        <td>
                                                            {{$user->net_id}}
                                                        </td>
                                                        <td>
                                                            {{$user->first_name}}
                                                        </td>
                                                        <td>
                                                            {{$user->last_name}}
                                                        </td>
                                                        <td>
                                                            {{$user->email}}
                                                        </td>

                                                        <td>
                                                            <button type="submit" name="update" class="btn btn-dark rounded-pill" onclick="window.location='{{ route('userSummary', ['courseUserId'=>$courseUser->user_id]) }}'">Summary!</button>
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
                <ul class="nav nav-pills " id="problemsInterventionsTA" role="tablist">

                    <li  style="margin:0 2px" class="nav-item">
                        <a class="nav-link active" id="pills-individualTA-tab" data-toggle="pill" href="#individualProblemsInterventionsTA" role="tab" aria-controls="individualProblemsInterventionsTA" aria-selected="false">Individual Problem Cases/ Interventions</a>
                    </li>

                    <li  style="margin:0 2px" class="nav-item">
                        <a class="nav-link" id="pills-groupTA-tab" data-toggle="pill" href="#groupProblemsInterventionsTA" role="tab" aria-controls="groupProblemsInterventionsTA" aria-selected="false">Group Problem Cases/ Interventions</a>
                    </li>
                </ul>

                <div class="tab-content" id="pills-problemsInterventionsTAContent">
                    <div class="tab-pane fade show active" id="individualProblemsInterventionsTA" role="tabpanel" aria-labelledby="pills-individualTA-tab">
                        <div class="row">
                            @include ('/interventions/weeks_problem_cases_subview_TA')
                            @include ('/interventions/weeks_interventions_subview_TA')
                        </div>
                    </div>

                    <div class="tab-pane fade show" id="groupProblemsInterventionsTA" role="tabpanel" aria-labelledby="pills-groupTA-tab">
                        <div class="row">
                            @include ('/group_interventions/weeks_group_problem_cases_subview_TA')
                            @include ('/group_interventions/weeks_group_interventions_subview_TA')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
