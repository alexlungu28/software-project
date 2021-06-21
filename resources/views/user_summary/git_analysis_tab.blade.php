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

                    @for($i = 1; $i <= 10; $i++)
                    @include('user_summary/git_analysis_week')
                    @endfor
                </div>

            </div>
        </div>
    </div>


