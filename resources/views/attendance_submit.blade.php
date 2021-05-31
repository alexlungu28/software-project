@extends('layouts.app', ['activePage' => 'attendance', 'titlePage' => __('Attendance')])

@section('content')
    <div class="content">
        <div class="container-fluid">

    <div class="table-responsive">
        <button type="submit" name="update" class="btn btn-dark rounded-pill" onclick="window.location='{{ route('week', [$attendances[0]->group_id, $attendances[0]->week]) }}'">Back!</button>
        <div class="col-xl-12">
            <h3>Attendance - {{App\Models\Group::find($attendances[0]->group_id)->group_name}}, Week {{$attendances[0]->week}}</h3>
            <div class="card">

                <div class="card-block table-border-style">
                    <div class="table-responsive">

                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Reason (if late or absent)</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($attendances as $at)
                                <tr>

                                    <td>{{App\Models\User::find($at->user_id)->first_name . " " . App\Models\User::find($at->user_id)->last_name }}</td>

                                    <td>
                                        @if($at->status == 1)
                                            <button class="btn btn-info rounded-pill" cursor="default" >Present</button>
                                        @elseif($at->status == 2)
                                            <button class="btn btn-warning rounded-pill" cursor="default" >Late</button>
                                        @elseif($at->status == 3)
                                            <button class="btn btn-danger rounded-pill" cursor="default" >Absent</button>
                                        @else
                                            {{" "}}
                                        @endif
                                    </td>



                                    <form id={{"attend" . $at->id}} method="post" value = "<?php echo csrf_token(); ?>" action="{{action('App\Http\Controllers\AttendanceController@update',$at->id)}}">
                                        @csrf
                                        <td> <textarea form={{"attend" . $at->id}} type="text" name="reason" class="form-control" >{{$at->reason}}</textarea> </td>
                                        <input type="hidden" name="_method" value="POST">
                                        <td>
                                            <button type="submit" name="update" class="btn btn-info " value="1">Present</button>
                                            <button type="submit" name="update" class="btn btn-warning rounded-pill" value="2">Late</button>
                                            <button type="submit" name="update" class="btn btn-danger rounded-pill" value="3">Absent</button>
                                        </td>
                                    </form>




                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
        <!-- [ Hover-table ] start-->

        <!-- [ Hover-table ] end -->

@endsection
