@extends('layouts.app', ['activePage' => 'attendance', 'titlePage' => __('Attendance')])

@section('content')
    <div class="content">
        <div class="container-fluid">

    <div class="table-responsive">

        <div class="col-xl-12">
            <h3>Notes - {{App\Models\Group::find($notes[0]->group_id)->group_name}}, Week {{$notes[0]->week}}</h3>
            <div class="card">

                <div class="card-block table-border-style">
                    <div class="table-responsive">

                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Week</th>
                                <th>Problem Signal</th>
                                <th>Note</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($notes as $note)
                                <tr>


                                    <td>{{App\Models\User::find($note->user_id)->first_name . " " . App\Models\User::find($note->user_id)->last_name }}</td>
                                    <td>{{$note->week}}</td>
                                    <td>{{$note->problem_signal}}</td>



                                    <form id="note" method="post" value = "<?php echo csrf_token(); ?>" action="{{action('App\Http\Controllers\NotesController@update',$note->id)}}">
                                        @csrf
                                        <td> <textarea form="note" type="text" name="note" class="form-control" >{{$note->note}}</textarea> </td>
                                        <input type="hidden" name="_method" value="POST">
                                        <td>
                                            <button type="submit" name="update" class="btn btn-success rounded-pill" value="1">Green</button>
                                            <button type="submit" name="update" class="btn btn-warning rounded-pill" value="2">Yellow</button>
                                            <button type="submit" name="update" class="btn btn-danger rounded-pill" value="3">Red</button>
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
