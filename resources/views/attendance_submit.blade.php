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
                                <th>Name</th>
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



                                    <form id="attend" method="post" value = "<?php echo csrf_token(); ?>" action="{{action('App\Http\Controllers\AttendanceController@update',$at->id)}}">
                                        @csrf
                                        <td> <textarea form="attend" type="text" name="reason" class="form-control" >{{$at->reason}}</textarea> </td>
                                        <input type="hidden" name="_method" value="POST">
                                        <td>
                                            <button type="submit" name="update" class="btn btn-info " value="Present">Present</button>
                                            <button type="submit" name="update" class="btn btn-warning rounded-pill" value="Late">Late</button>
                                            <button type="submit" name="update" class="btn btn-danger rounded-pill" value="Absent">Absent</button>
                                        </td>
                                    </form>




                                </tr>
                            @endforeach


                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>

                                        <li>{{ $errors->first() }}</li>

                                    </ul>
                                </div>
                            @endif
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
