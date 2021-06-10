@extends('layouts.app', ['activePage' => 'group', 'titlePage' => __('Weeks')])

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


@section('content')
    <div class="content">
        <div class="container-fluid">
            <button type="submit" name="update" class="btn btn-dark rounded-pill" onclick="window.location='{{ route('groups', ['edition_id'=>$edition_id]) }}'">Back!</button>
            <div class="row">
                @for($w=1; $w<=10; $w++)
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats" style="width: 120px;">
                            <div class="card-icon">
                                <a class="nav-link" href="{{ route('week', [$group_id, $w]) }}">
                                    <p>Week {{ $w }}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
            <div class="row"  >
                <div class="col-lg-6 col-md-12">
                    <div class="card" >
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Problem cases without interventions</h4>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="profile">
                                    <table class="table" style="table-layout:fixed;">
                                        <thead class="text-primary">
                                        <th>Name</th>
                                        <th style="width:10%">Week</th>
                                        <th style="width:40%">Note</th>

                                        <th style="width:10%">Signal</th>
                                        <th></th>
                                        </thead>
                                        <tbody>

                                        @php
                                            $notes = App\Models\NoteGroup::where('group_id', '=', $group->id)->orderBy('week')->first()->get();
                                        $notesGood = [];
                                        foreach($notes as $note) {
                                            array_push($notesGood, $note);
                                        }
                                        $interventions = \App\Models\Intervention::where('group_id','=', $group->id)->get();
                                        $interventionNotes = [];
                                        foreach($interventions as $intervention) {
                                            if(preg_match("/^(aaaaanote)\d+$/i", $intervention->reason)) {
                                                $note = App\Models\Note::find(preg_replace('/[^0-9]/', '', $intervention->reason));
                                                array_push($interventionNotes, $note);
                                            }
                                        }


                                        $notesNoInterventions = array_diff($notesGood, $interventionNotes);
                                        //return dd($notesNoInterventions);


                                        @endphp






                                        @foreach($group->groupnotes->sortBy('week') as $groupnote)
                                            @if($groupnote->problem_signal >= 2)
                                            <tr>
                                                <td>
                                                    Group Note
                                                </td>
                                                <td>
                                                    {{$groupnote->week}}
                                                </td>

                                                <td >
                                                    {{$groupnote->note}}
                                                </td>
                                                <td>
                                                   @if($groupnote->problem_signal == 2)
                                                        <button title="Warning!" class="btn btn-squared-default btn-warning">
                                                            <br />
                                                            <br />
                                                        </button>
                                                    @else
                                                        <button title="Problematic!"  class="btn btn-squared-default btn-danger">
                                                            <br />
                                                            <br />
                                                        </button>
                                                    @endif
                                                </td>

                                                <td>

                                                    <button class="btn btn-primary btn-sm rounded-0" type="button" name="update"  data-toggle="modal" data-target="{{"#createInterventionNote" . $note->id}}">
                                                        <span>Create</span>
                                                        <br>
                                                        <span>Intervention</span>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach


                                        @php
                                            $notes = App\Models\Note::where('group_id', '=', $group->id)->orderBy('week')->get();
                                        $notesGood = [];
                                        foreach($notes as $note) {
                                            array_push($notesGood, $note);
                                        }
                                        $interventions = \App\Models\Intervention::where('group_id','=', $group->id)->get();
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

                                            @php


                                            @endphp
                                            @if($note->problem_signal >= 2)
                                                <tr>
                                                    <td>
                                                        {{$note->user->first_name . ' ' . $note->user->last_name}}
                                                    </td>
                                                    <td>
                                                        {{$note->week}}
                                                    </td>
                                                   <td > <div style=" overflow-x: hidden; overflow-y:auto;
   text-overflow: clip;
   display: -webkit-box;
   -webkit-line-clamp: 5; /* number of lines to show */
   -webkit-box-orient: vertical;">
                                                        {{$note->note}}
                                                            </div>
                                                    </td>
                                                    <td>
                                                        @if($note->problem_signal == 2)
                                                            <button title="Warning!" class="btn btn-squared-default btn-warning">
                                                                <br />
                                                                <br />
                                                            </button>
                                                        @else
                                                            <button title="Problematic!"  class="btn btn-squared-default btn-danger">
                                                                <br />
                                                                <br />
                                                            </button>
                                                        @endif

                                                    </td>
                                                    <td>
                                                        @include ('/interventions/intervention_create_note_based_modal')
                                                        <button class="btn btn-primary btn-sm rounded-0" type="button" name="update"  data-toggle="modal" data-target="{{"#createInterventionNote" . $note->id}}">
                                                            <span>Create</span>
                                                            <br>
                                                            <span>Intervention</span>
                                                        </button>


                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-6 col-md-12">
                    <div class="card" >
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Active Interventions</h4>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="profile">
                                    <table class="table">
                                        <thead class="text-primary">
                                        <th>Name</th>
                                        <th>Reason</th>
                                        <th style="width:15%">Action</th>
                                        <th>Starting</th>
                                        <th>Ending</th>
                                        <th>Solved</th>
                                        </thead>
                                        <tbody>
                                        {{--
                                        @foreach($group->groupnotes->sortBy('week') as $groupnote)
                                            @if($groupnote->problem_signal >= 2)
                                                <tr>
                                                    <td>

                                                    </td>
                                                    <td>
                                                        {{$groupnote->week}}
                                                    </td>
                                                    <td>
                                                        Group
                                                    </td>
                                                    <td>

                                                    </td>
                                                    <td>
                                                        {{$groupnote->problem_signal}}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        --}}


                                        @foreach($group->groupIndividualInterventions->sortBy('end_day') as $intervention)

                                                <tr>
                                                    <td>{{App\Models\User::find($intervention->user_id)->first_name . " " . App\Models\User::find($intervention->user_id)->last_name }}</td>


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

                                                    <td>@php echo date("F jS, Y", strtotime($intervention->start_day)); @endphp</td>

                                                    <td>@php echo date("F jS, Y", strtotime($intervention->end_day)); @endphp</td>

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









        </div>
    </div>
@endsection
