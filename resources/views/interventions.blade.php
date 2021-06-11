@extends('layouts.app', ['activePage' => 'interventions', 'titlePage' => __('Interventions')])
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">



</head>
@section('content')



    <div class="content">
        <div class="container-fluid">

            @include ('/interventions/intervention_create_modal')





            <ul class="nav nav-pills " id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-allInterventions-tab" data-toggle="pill" href="#allInterventions" role="tab" aria-controls="allInterventions" aria-selected="true">Interventions - Individual</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-problemCases-tab" data-toggle="pill" href="#problemCases" role="tab" aria-controls="problemCases" aria-selected="false">Problem Cases - Individual</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Interventions - Group</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Problem Cases - Group</a>
                </li>

            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="allInterventions" role="tabpanel" aria-labelledby="pills-allInterventions-tab">

                    <div class="table-responsive">

                        <h3>Interventions</h3>



                        <button type="button" name="createIntervention" class="btn btn-danger rounded-pill" data-toggle="modal" data-target="{{"#createIntervention" . $edition_id}}" style="float:right">Create Intervention</button>

                        <div class="card">

                            <div class="card-block table-border-style">
                                <div class="table-responsive">

                                    <table class="table table-hover" style="table-layout:fixed;">
                                        <thead>
                                        <tr>
                                            <th style="width:15%">Name</th>
                                            <th style="width:10%">Group</th>
                                            <th style="width:25%">Reason</th>
                                            <th style="width:25%">Action</th>
                                            <th style="width:10%">Starting</th>
                                            <th style="width:10%">Ending</th>
                                            <th style="width:10%"></th>
                                            <th style="width:20%"></th>

                                        </tr>
                                        </thead>
                                        <tbody>


                                        @foreach($interventions as $intervention)

                                            <tr>

                                                <td>{{App\Models\User::find($intervention->user_id)->first_name . " " . App\Models\User::find($intervention->user_id)->last_name }}</td>
                                                <td>

                                                    {{App\Models\Group::find($intervention->group_id)->group_name}}</td>

                                                <td>





                                                    @if(preg_match("/^(note)\d+$/i", $intervention->reason))
                                                        @php
                                                            $note = App\Models\Note::find(preg_replace('/[^0-9]/', '', $intervention->reason));
                                                            @endphp

                                                    @include('/interventions/intervention_view_note')
                                                        <button type="button" name="viewNote" class="btn btn-info rounded-pill" data-toggle="modal" data-target="{{"#viewNote" . preg_replace('/[^0-9]/', '', $intervention->reason)}}">Note</button>
                                                    @else
                                                        {{$intervention->reason}}
                                                    @endif
                                                </td>

                                                <td>{{$intervention->action}}
                                                </td>

                                                <td>@php echo date("F jS", strtotime($intervention->start_day)); @endphp</td>

                                                <td>@php echo date("F jS", strtotime($intervention->end_day)); @endphp</td>



                                                <form>
                                                    @csrf
                                                    <input type="hidden" name="_method" value="POST">
                                                    <td align="right">

                                                        <button type="button" name="update" class="btn btn-success " value="1"  data-toggle="modal" data-target="">
                                                            Active</button>



                                                    </td>
                                                </form>



                                                <form>
                                                    @csrf
                                                    <input type="hidden" name="_method" value="POST">
                                                    <td align="right">
                                                        <button type="button" name="update" class="btn btn-info " value="1"  data-toggle="modal" data-target="{{"#editIntervention" . $intervention->id}}">Edit</button>
                                                        <button type="button" name="update" class="btn btn-dark" value="2" data-toggle="modal" data-target="{{"#deleteIntervention" . $intervention->id}}"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                            </svg></button>

                                                    </td>
                                                </form>


                                            </tr>

                                            @include ('/interventions/intervention_edit_modal')
                                            @include ('/interventions/intervention_delete_modal')

                                        @endforeach



                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div></div>

                </div>


                <div class="tab-pane fade show" id="problemCases" role="tabpanel" aria-labelledby="pills-problemCases-tab">
                    <div class="table-responsive">

                        <h3>Problem Cases - Individual</h3>

                        <div class="card">

                            <div class="card-block table-border-style">
                                <div class="table-responsive">

                                    <table class="table table-hover" style="table-layout:fixed;">
                                        <thead>
                                        <tr>
                                            <th style="width:15%">Name</th>
                                            <th style="width:10%">Group</th>
                                            <th style="width:10%">Week</th>
                                            <th style="width:15%">Problem Signal</th>
                                            <th style="width:30%">Note</th>
                                            <th style="width:20%"></th>

                                        </tr>
                                        </thead>
                                        <tbody>

                                        @php

                                        $notesGood = [];
                                        foreach($notes as $note) {
                                            array_push($notesGood, $note);
                                        }
                                        $interventionNotes = [];
                                        foreach($interventions as $intervention) {
                                            if(preg_match("/^(note)\d+$/i", $intervention->reason)) {
                                                $note = App\Models\Note::find(preg_replace('/[^0-9]/', '', $intervention->reason));
                                                array_push($interventionNotes, $note);
                                            }
                                        }


                                        $notesNoInterventions = array_diff($notesGood, $interventionNotes);
                                        //return dd($notesNoInterventions);


                                        @endphp



                                        @foreach($notesNoInterventions as $note)

                                            <tr>


                                                <td>{{App\Models\User::find($note->user_id)->first_name . " " . App\Models\User::find($note->user_id)->last_name }}</td>
                                                <td>{{App\Models\Group::find($note->group_id)->group_name}}</td>
                                                <td>Week {{$note->week}}</td>
                                                <td>@if($note->problem_signal == 1)
                                                        <button class="btn btn-success rounded-pill" cursor="default" >All good!</button>
                                                    @elseif($note->problem_signal == 2)
                                                        <button class="btn btn-warning rounded-pill" cursor="default" >Warning!</button>
                                                    @elseif($note->problem_signal == 3)
                                                        <button class="btn btn-danger rounded-pill" cursor="default" >Problematic!</button>
                                                    @else
                                                        {{$problemSignal = " "}}
                                                    @endif</td>



                                                <form id={{"note" . $note->id}} method="post" value = "<?php echo csrf_token(); ?>" action="">
                                                    @csrf
                                                    <td> {{$note->note}} </td>
                                                    <input type="hidden" name="_method" value="POST">
                                                    <td>
                                                        <button type="button" name="update" class="btn btn-info " value="1"  data-toggle="modal" data-target="{{"#createInterventionNote" . $note->id}}">Create Intervention</button>
                                                    </td>
                                                </form>


                                                @include ('/interventions/intervention_create_note_based_modal')



                                            </tr>
                                        @endforeach



                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div></div>

                    </div>



                <div class="tab-pane fade show" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                <p>salut</p>
                </div>

            </div>

</div>



@endsection
