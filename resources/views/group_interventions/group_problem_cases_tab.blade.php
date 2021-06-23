<div class="tab-pane fade show" id="groupProblemCases" role="tabpanel" aria-labelledby="pills-groupProblemCases-tab">
    <div class="table-responsive">

        <h3>Problem Cases - Group</h3>

        <div class="card">
            <div class="card-block table-border-style">

                <div class="table-responsive">
                    <table class="table table-hover" style="table-layout:fixed;">
                        <thead>
                        <tr>
                            <th style="width:15%">Group</th>
                            <th style="width:10%">Week</th>
                            <th style="width:20%">Problem Signal</th>
                            <th style="width:50%">Note</th>
                            <th style="width:20%"></th>
                        </tr>
                        </thead>

                        <tbody>

                        @foreach($groupNotesNoInterventions as $note)
                            <tr>

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
                                        <button type="button" name="update" class="btn btn-info " value="1"  data-toggle="modal" data-target="{{"#createGroupInterventionNote" . $note->id}}">Create Group Intervention</button>
                                    </td>
                                </form>
                                @include ('/group_interventions/create_note_based_modal')
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
