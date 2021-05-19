@extends('layouts.app', ['activePage' => 'rubricView', 'titlePage' => __('RubricView')])

@section('content')
    <div class="content">
        <div class="container-fluid">
    <div class="table-responsive">
        <div class="col-xl-12">
            <div class="card">

                <div class="card-block table-border-style">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Student Id</th>
                                <th>Week</th>
                                <th>Present</th>
                                <th>Reason</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($attendances as $at)
                                <tr>

                                    <td>{{App\Models\User::find($at->user_id)->first_name . " " . App\Models\User::find($at->user_id)->last_name }}</td>
                                    <td>{{$at->week}}</td>
                                    <td>{{$at->present}}</td>



                                    <form method="post" value = "<?php echo csrf_token(); ?>" action="{{action('App\Http\Controllers\AttendanceController@update',$at->id)}}">
                                        @csrf
                                        <td> <textarea name="reason" >{{$at->reason}}</textarea> </td>
                                        <input type="hidden" name="_method" value="POST">
                                        <td><button type="submit" name="update" class="btn btn-danger rounded-pill" value="0">Mark Absent</button>
                                            <button type="submit" name="update" class="btn btn-info " value="1">Mark Present</button>
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
