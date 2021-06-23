<div class="tab-pane fade show" id="buddyCheckTab" role="tabpanel" aria-labelledby="pills-buddy-check-tab">

    <div class="content">
        <div class="container-fluid">
            <ul class="nav nav-pills " id="pills-tab" role="tablist">
                @for($i = 1; $i <= 10; $i++)
                    @if(DB::table('buddychecks')->where('group_id', "=", $groupId)->where('week', '=', $i)->exists())
                    <li class="nav-item">
                        <a class="nav-link" id="pills-{{"buddyCheckWeek" . $i}}" data-toggle="pill" href="{{"#buddyCheckWeek" . $i}}" role="tab" aria-controls="pills-{{"buddyCheckWeek" . $i}}" aria-selected="false">Week {{$i}}</a>
                    </li>
                    @endif
                @endfor

            </ul>


            <div class="tab-content" id="pills-tabContent">

                <!-- Including git analysis subviews for each week -->
                @for($week = 1; $week <= 10; $week++)
                    @if(DB::table('buddychecks')->where('group_id', "=", $groupId)->where('week', '=', $week)->exists())
                    @include('user_summary/buddy_check_week')
                    @endif

                @endfor
            </div>

        </div>
    </div>
    </div>

