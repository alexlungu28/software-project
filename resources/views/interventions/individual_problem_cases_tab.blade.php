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

                        @foreach($notesNoInterventions as $note)
                            <tr>
                                <td>{{App\Models\User::find($note->user_id)->first_name . " " . App\Models\User::find($note->user_id)->last_name }}</td>

                                <td>{{App\Models\Group::find($note->group_id)->group_name}}</td>

                                <td>Week {{$note->week}}</td>

                                <td>
                                    @if($note->problem_signal == 1)
                                        <button class="btn btn-success rounded-pill" cursor="default" >All good!</button>
                                    @elseif($note->problem_signal == 2)
                                        <button class="btn btn-warning rounded-pill" cursor="default" >Warning!</button>
                                    @elseif($note->problem_signal == 3)
                                        <button class="btn btn-danger rounded-pill" cursor="default" >Problematic!</button>
                                    @else
                                        {{$problemSignal = " "}}
                                    @endif
                                </td>

                                <form id={{"note" . $note->id}} method="post" value = "<?php echo csrf_token(); ?>" action="">
                                    @csrf
                                    <td>  <div style="overflow-x: hidden; overflow-y:auto;
                                                                   text-overflow: clip;
                                                                   display: -webkit-box;
                                                                   -webkit-line-clamp: 5; /* number of lines to show */
                                                                   -webkit-box-orient: vertical;">
                                            {{$note->note}}
                                        </div> </td>

                                    <input type="hidden" name="_method" value="POST">
                                    <td>
                                        <button type="button" name="update" class="btn btn-info " value="1"  data-toggle="modal" data-target="{{"#createInterventionNote" . $note->id}}">Create Intervention</button>
                                    </td>
                                </form>
                                @include ('/interventions/create_note_based_modal')
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
