@extends('layouts.app', ['activePage' => 'notes', 'titlePage' => __('Notes')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <button type="submit" name="update" class="btn btn-dark rounded-pill" onclick="window.location='{{ route('week', [$notes[0]->group_id, $notes[0]->week]) }}'">Back!</button>
    <div class="table-responsive">

        <div class="col-xl-12">
            <h3>Notes - {{App\Models\Group::find($notes[0]->group_id)->group_name}}, Week {{$notes[0]->week}}</h3>
            <div class="card">

                <div class="card-block table-border-style">
                    <div class="table-responsive">

                        <table class="table table-hover">
                            <thead>
                            <tr>

                                <th>Problem Signal for {{App\Models\Group::find($notes[0]->group_id)->group_name}}</th>
                                <th>Group Note</th>
                                <th>Select Problem Signal</th>

                            </tr>
                            </thead>
                            <tbody>

                            @foreach($groupNotes as $note)


                                <tr>



                                    <td  style="width:30%">@if($note->problem_signal == 1)
                                            <button class="btn btn-success rounded-pill" cursor="default" >All good!</button>
                                        @elseif($note->problem_signal == 2)
                                            <button class="btn btn-warning rounded-pill" cursor="default" >Warning!</button>
                                        @elseif($note->problem_signal == 3)
                                            <button class="btn btn-danger rounded-pill" cursor="default" >Problematic!</button>
                                        @else
                                            {{$problemSignal = " "}}
                                        @endif</td>



                                    <form id="groupNote" method="post" value = "<?php echo csrf_token(); ?>" action="{{action('App\Http\Controllers\NotesController@groupNoteUpdate',$note->id)}}">
                                        @csrf
                                        <td style="width:40%"> <textarea form="groupNote" type="text" name="groupNote" class="form-control" rows="5">{{$note->note}}</textarea> </td>
                                        <input type="hidden" name="_method" value="POST">
                                        <td  style="width:30%">
                                            <button type="submit" name="groupNoteUpdate" class="btn btn-success rounded-pill" value="1">All good!</button>
                                            <button type="submit" name="groupNoteUpdate" class="btn btn-warning rounded-pill" value="2">Warning!</button>
                                            <button type="submit" name="groupNoteUpdate" class="btn btn-danger rounded-pill" value="3">Problematic!</button>
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
            <div class="table-responsive">

                <div class="col-xl-12">
                    <h3>Individual Notes</h3>

                    <div class="card">

                        <div class="card-block table-border-style">
                            <div class="table-responsive">

                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Problem Signal</th>
                                        <th>Note</th>
                                        <th>Select Problem Signal</th>

                                    </tr>
                                    </thead>
                                    <tbody>



                                    @foreach($notes as $note)

                                        <tr>


                                            <td  style="width:15%">{{App\Models\User::find($note->user_id)->first_name . " " . App\Models\User::find($note->user_id)->last_name }}</td>
                                            <td  style="width:15%">@if($note->problem_signal == 1)
                                                    <button class="btn btn-success rounded-pill" cursor="default" >All good!</button>
                                                @elseif($note->problem_signal == 2)
                                                    <button class="btn btn-warning rounded-pill" cursor="default" >Warning!</button>
                                                @elseif($note->problem_signal == 3)
                                                    <button class="btn btn-danger rounded-pill" cursor="default" >Problematic!</button>
                                                @else
                                                    {{$problemSignal = " "}}
                                                @endif</td>



                                            <form id={{"note" . $note->id}} method="post" value = "<?php echo csrf_token(); ?>" action="{{action('App\Http\Controllers\NotesController@update',$note->id)}}">
                                                @csrf
                                                <td style="width:40%"> <textarea form={{"note" . $note->id}} type="text" name="reason" class="form-control" rows="3" placeholder="Please fill in the note!">{{$note->note}}</textarea> </td>
                                                <input type="hidden" name="_method" value="POST">
                                                <td  style="width:30%">
                                                    <button type="submit" name="update" class="btn btn-success rounded-pill" value="1">All good!</button>
                                                    <button type="submit" name="update" class="btn btn-warning rounded-pill" value="2">Warning!</button>
                                                    <button type="submit" name="update" class="btn btn-danger rounded-pill" value="3">Problematic!</button>
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
