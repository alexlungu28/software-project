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
                </script>
        </head>

    <div class="content">
        <div class="container-fluid">
            <button type="submit" name="update" class="btn btn-dark rounded-pill" onclick="window.location='{{ route('group', ['group_id'=>$group_id]) }}'">Back!</button>
            <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats" style="width: 120px;">
                            <div class="card-icon">
                                <a class="nav-link" href="{{ route('attend', [$group_id, $week]) }}">
                                    <p>Attendance</p>
                                </a>
                            </div>
                        </div>
                    </div>
                @foreach($rubrics as $rubric)
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats" style="width: 120px;">
                            <div class="card-icon">
                                <a class="nav-link" href="{{ route('rubric', [$rubric->id, $group_id]) }}">
                                    <p>Rubric {{ $rubric->name }}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats" style="width: 120px;">
                        <div class="card-icon">
                            <a class="nav-link" href="{{ route('note', [$group_id, $week]) }}">
                                <p>Notes</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container" style="display:inline-flex;">
            <div class="card bg-light mt-5" style="color: black; font-size: 1.2rem; width: 500px; margin-right: 20px">
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

            <div class="card bg-light mt-5" style="color: black; font-size: 1.2rem; width: 500px">
                <div class="card-header">
                    Buddycheck importing
                </div>
                <div class="card-body">
                    <form action="{{ route('importBuddycheck', [$group_id, $week]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" class="form-control" required>
                        <br>
                        <button class="btn btn-info">Import Buddycheck from a .csv file with students in this group</button>
                    </form>
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
        </div>

    </div>
@endsection
