@extends('layouts.app', ['activePage' => 'notes', 'titlePage' => __('Notes')])

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
                                <th>Problem Signal</th>
                                <th>Note</th>

                            </tr>
                            </thead>
                            <tbody>

                            @foreach($notes as $note)


                                <tr>


                                    <td>{{App\Models\User::find($note->user_id)->first_name . " " . App\Models\User::find($note->user_id)->last_name }}</td>
                                    <td>@if($note->problem_signal == 1)
                                            <button class="btn btn-success rounded-pill" cursor="default" >All good!</button>
                                        @elseif($note->problem_signal == 2)
                                            <button class="btn btn-warning rounded-pill" cursor="default" >Warning!</button>
                                        @elseif($note->problem_signal == 3)
                                            <button class="btn btn-danger rounded-pill" cursor="default" >Problematic!</button>
                                        @else
                                            {{$problemSignal = " "}}
                                        @endif</td>



                                    <form id="note" method="post" value = "<?php echo csrf_token(); ?>" action="{{action('App\Http\Controllers\NotesController@update',$note->id)}}">
                                        @csrf
                                        <td> <textarea form="note" type="text" name="note" class="form-control" >{{$note->note}}</textarea> </td>
                                        <input type="hidden" name="_method" value="POST">
                                        <td>
                                            <button type="submit" name="update" class="btn btn-success rounded-pill" value="1">All good!</button>
                                            <button type="submit" name="update" class="btn btn-warning rounded-pill" value="2">Warning!</button>
                                            <button type="submit" name="update" class="btn btn-danger rounded-pill" value="3">Problematic!</button>
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
