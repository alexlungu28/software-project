<div class="tab-pane fade show" id="{{"gitAnalysisWeek" . $week}}" role="tabpanel" aria-labelledby="pills-{{"gitAnalysisWeek" . $week}}">

    <div class="content">
        <head>
            <meta charset="utf-8" />
            <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
            <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
            <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
            <script>
                $(document).ready( function () {
                    $('#tableGit{!! $week !!}').DataTable();
                } );
            </script>
        </head>

        <div class="container-fluid" style="margin: 20px; margin-bottom: 80px" >
            <div id="chartContainer{{$week}}" style="height: 370px; width: 100%;"></div>
            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
        </div>

        <div class="container-fluid">
            @if(DB::table('gitanalyses')->where('group_id', "=", $groupId)->where('week_number', '=', $week)->exists())
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">GitInspector Details</h4>
                    </div>

                    <div class="card-body">
                        <table id ="tableGit{!! $week !!}" class="table">

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
</div>
