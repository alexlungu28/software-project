<div class="tab-pane fade show" id="{{"buddyCheckWeek" . $week}}" role="tabpanel" aria-labelledby="pills-{{"buddyCheckWeek" . $week}}">
    @if(DB::table('buddychecks')->where('group_id', "=", $groupId)->where('week', '=', $week)->exists())
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
                    First Name
                </th>
                <th>
                    Last Name
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
                    @php
                    $user = DB::table('users')->where('id', '=', $buddycheck->user_id)->get()->first();
                    @endphp
                    <tr>
                        <td>
                            {{$user->net_id}}
                        </td>
                        <td>
                            {{$user->first_name}}
                        </td>
                        <td>
                            {{$user->last_name}}
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
