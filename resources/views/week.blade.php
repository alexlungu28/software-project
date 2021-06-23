@extends('layouts.app', ['activePage' => 'group', 'titlePage' => __('Week')])

@section('content')

    <?php
    /**
     * This if goes through the gitanalysis found in the previous for is good or doesn't have any gitanalyses
     * otherwise it initiates the datapoints with dummy data
     */
    if (DB::table('gitanalyses')->where('group_id', "=", $group_id)->where('week_number', '=', $week)->exists()) {
        $names = json_decode($gitanalyses[0]->names);
        $emails = json_decode($gitanalyses[0]->emails);
        $blame = json_decode($gitanalyses[0]->blame);
        $activity = json_decode($gitanalyses[0]->activity);

        $dataPoints = array_fill(0, count($emails), null);
        $count = 0;
        foreach ($emails as $email) {
            $dataPoints[$count] = array("label"=>"$email", "y"=>$activity[$count]->percentage_of_changes);
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
    if (DB::table('buddychecks')->where('group_id', '=', $group_id)->where('week', '=', $week)->exists()) {
        $buddyChecksNumber = 0;
        foreach ($buddychecks as $buddycheck) {
            $buddyChecksNumber++;
        }
        //ddd($buddychecks[0]);
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
                <meta charset="utf-8" />
                <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
                <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
                <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
                <script>
                    $(document).ready( function () {
                        $('#table').DataTable();
                    } );
                    $(document).ready( function () {
                        $('#second-table').DataTable();
                    } );
                </script>
        </head>


        <button style="margin-left: 20px" type="submit" name="update" class="btn btn-dark rounded-pill" onclick="window.location='{{ route('group', ['group_id'=>$group_id]) }}'">Back!</button>
        <div class="container-fluid" style="display: inline-flex">
            <div class="container" >
                <div class="row">
                            <div class="card card-stats" style="width: 120px; margin-left:10px; margin-right:10px; float:left">
                                <div class="card-icon">
                                    <a class="nav-link" href="{{ route('attend', [$group_id, $week]) }}">
                                        <p>Attendance</p>
                                    </a>
                                </div>
                            </div>
                        <div class="card card-stats" style="width: 120px; margin-left:10px; margin-right:10px; float:left">
                            <div class="card-icon">
                                <a class="nav-link" href="{{ route('note', [$group_id, $week]) }}">
                                    <p>Notes</p>
                                </a>
                            </div>
                        </div>
                    @foreach($rubrics as $rubric)
                            <div class="card card-stats" style="width: 120px; margin-left:10px; margin-right:10px; float:left">
                                <div class="card-icon">
                                    <a class="nav-link" href="{{ route('rubric', [$rubric->id, $group_id]) }}">
                                        <p>Rubric {{ $rubric->name }}</p>
                                    </a>
                                </div>
                            </div>
                    @endforeach
                </div>
            </div>

            <div class="container" style="display:inline;">
                <div class="card bg-light" style="color: black; font-size: 1.2rem; width: 500px; margin-right: 20px">
                    <div class="card-header">
                        Git analysis importing
                    </div>
                    <div class="card-body">
                        <form action="{{ route('importGitanalysis', [$group_id, $week]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" class="form-control" required>
                            <br>
                            <button class="btn btn-info">Import Git analysis from a txt file containing JSON</button>
                        </form>
                    </div>
                </div>

                <div class="card bg-light" style="color: black; font-size: 1.2rem; width: 500px">
                    <div class="card-header">
                        Buddycheck importing
                    </div>
                    <div class="card-body">
                        <form action="{{ route('importBuddycheck', [$group_id, $week]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" class="form-control" required>
                            <br>
                            <button class="btn btn-info">Import Buddycheck from a .csv file</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            @if(DB::table('gitanalyses')->where('group_id', "=", $group_id)->where('week_number', '=', $week)->exists())
                <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Gitinspector Details</h4>
                    </div>
                <div class="card-body">
                    <table id ="table" class="table">
                        <thead class=" text-primary">
                        <th>
                            Name
                        </th>
                        <th>
                            Commits
                        </th>
                        <th>
                            Insertions
                        </th>
                        <th>
                            Deletions
                        </th>
                        <th>
                            Rows
                        </th>
                        <th>
                            Stability
                        </th>
                        <th>
                            Age
                        </th>
                        <th>
                            Percentage in comments
                        </th>
                        </thead>
                        <tbody>
                        @for($i = 0;$i < count($names); $i++)
                        <tr>
                            <td>
                                {{$names[$i]}}
                            </td>
                            <td>
                                {{$activity[$i]->commits}}
                            </td>
                            <td>
                                {{$activity[$i]->insertions}}
                            </td>
                            <td>
                                {{$activity[$i]->deletions}}
                            </td>
                            <td>
                                {{$blame[$i]->rows}}
                            </td>
                            <td>
                                {{$blame[$i]->stability}}
                            </td>
                            <td>
                                {{$blame[$i]->age}}
                            </td>
                            <td>
                                {{$blame[$i]->percentage_in_comments}}
                            </td>

                        </tr>
                        @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
            @if(DB::table('buddychecks')->where('group_id', '=', $group_id)->where('week', '=', $week)->exists())
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Buddycheck Details</h4>
                    </div>
                    <div class="card-body">
                        <table id ="second-table" class="table">
                            <thead class=" text-primary">
                            <th>
                                Net ID
                            </th>
                            <th>
                                Last Name
                            </th>
                            <th>
                                First Name
                            </th>
                            <th>
                                Indicator
                            </th>
                            <th>
                                Average with Self
                            </th>
                            <th>
                                Q1 with self: Job Performance
                            </th>
                            <th>
                                Q2 with self: Attitude
                            </th>
                            <th>
                                Q3 with self: Leadership / Initiative
                            </th>
                            <th>
                                Q4 with self: Management of Resources
                            </th>
                            <th>
                                Q5 with self: Communication
                            </th>
                            </thead>
                            <tbody>
                            @foreach($buddychecks as $buddycheck)
                                <tr>
                                    <td>
                                        {{DB::table('users')
                                                ->where('id', '=', $buddycheck->user_id)
                                                ->value('net_id')}}
                                    </td>
                                    <td>
                                        {{DB::table('users')
                                                ->where('id', '=', $buddycheck->user_id)
                                                ->value('last_name')}}
                                    </td>
                                    <td>
                                        {{DB::table('users')
                                                ->where('id', '=', $buddycheck->user_id)
                                                ->value('first_name')}}
                                    </td>
                                    @php($data = json_decode($buddycheck->data))
                                    @foreach ($data as $key => $entry)
                                        @if($key == "Notes")
                                            <td>
                                                {{$entry}}
                                            </td>
                                        @endif
                                        @if($key == "Avg with self")
                                            <td>
                                                {{$entry}}
                                            </td>
                                        @endif
                                            @if($key == "Q1 with self: Job Performance")
                                                <td>
                                                    {{$entry}}
                                                </td>
                                            @endif
                                            @if($key == "Q2 with self: Attitude")
                                                <td>
                                                    {{$entry}}
                                                </td>
                                            @endif
                                            @if($key == "Q3 with self: Leadership / Initiative")
                                                <td>
                                                    {{$entry}}
                                                </td>
                                            @endif
                                            @if($key == "Q4 with self: Management of Resources")
                                                <td>
                                                    {{$entry}}
                                                </td>
                                            @endif
                                            @if($key == "Q5 with self: Communication")
                                                <td>
                                                    {{$entry}}
                                                </td>
                                            @endif
                                    @endforeach
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
        </div>
                @endif

    </div>
@endsection
