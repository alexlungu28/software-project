<div class="tab-pane fade show" id="gitAnalysisTab" role="tabpanel" aria-labelledby="pills-git-analysis-tab">

    <div class="content">
        <div class="container-fluid">
                <ul class="nav nav-pills " id="pills-tab" role="tablist">
                    @for($i = 1; $i <= 10; $i++)
                    <li class="nav-item">
                        <a class="nav-link" id="pills-{{"gitAnalysisWeek" . $i}}" data-toggle="pill" href="{{"#gitAnalysisWeek" . $i}}" role="tab" aria-controls="pills-{{"gitAnalysisWeek" . $i}}" aria-selected="false">Week {{$i}}</a>
                    </li>
                    @endfor

                </ul>


                <div class="tab-content" id="pills-tabContent">

                    <!-- Script for:
                     - generating the needed fields for the git analysis table,
                     - rendering the piechart for each week.
                     -->
                    <script>
                        window.onload = function() {

                            @for ($week = 1; $week <= 10; $week = $week + 1)
                            <?php

                            if (DB::table('gitanalyses')->where('group_id', "=", $groupId)->where('week_number', '=', $week)->exists()) {

                                $names = json_decode($gitanalyses[0]->names);
                                $emails = json_decode($gitanalyses[0]->emails);
                                $blame = json_decode($gitanalyses[0]->blame);
                                $activity = json_decode($gitanalyses[0]->activity);

                                $dataPoints = array_fill(0, count($emails), null);
                                $count = 0;
                                foreach ($emails as $email) {
                                    $dataPoints[$count] = array("label" => "$email", "y" => $activity[$count]->percentage_of_changes);
                                    $count++;
                                }

                            } else {
                                $dataPoints = [];
                            }
                            ?>

                            var chartContainerId = "chartContainer{{$week}}";

                            chart = new CanvasJS.Chart(chartContainerId, {
                                animationEnabled: true,
                                title: {
                                    text: "GitInspector"
                                },
                                data: [{
                                    type: "pie",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabel: "{label} ({y})",
                                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                            if({{count($dataPoints)}} > 0)
                            chart.render();
                            @endfor
                        }
                    </script>

                <!-- Including git analysis subviews for each week -->
                @for($week = 1; $week <= 10; $week++)
                    @include('user_summary/git_analysis_week')
                    @endfor
                </div>

            </div>
        </div>
    </div>


