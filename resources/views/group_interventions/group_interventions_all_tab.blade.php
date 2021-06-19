<div class="tab-pane fade show" id="allGroupInterventions" role="tabpanel" aria-labelledby="pills-allGroupInterventions-tab">

    <button type="button" name="createGroupIntervention" class="btn btn-danger rounded-pill" data-toggle="modal" data-target="{{"#createGroupIntervention" . $edition_id}}" style="float:right">Create Group Intervention</button>
    @include ('/group_interventions/group_intervention_create_modal')


    <div class="table-responsive">

        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready( function () {
                $('#tableGroupInterventions').DataTable({
                    "order" : []
                });
            } );

        </script>



        <div class="card">
            <div class="card-header card-header-primary">
                <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
                <h4 class="card-title ">Group Interventions</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">

                    <table class="table table-hover" id="tableGroupInterventions" style="table-layout:fixed; overflow:auto">
                        <thead>
                        <tr>
                            <th style="width:15%">Group</th>
                            <th style="width:30%">Reason</th>
                            <th style="width:30%">Action</th>
                            <th style="width:10%">Starting</th>
                            <th style="width:10%">Ending</th>
                            <th style="width:10%">Status</th>
                            <th style="width:20%"></th>
                        </tr>
                        </thead>
                        <tbody>


                        @foreach($groupInterventions as $intervention)
                            <tr>

                                <td>
                                    {{App\Models\Group::find($intervention->group_id)->group_name}}</td>
                                <td>
                                    @if(preg_match("/^(groupNote)\d+$/i", $intervention->reason))
                                        @php
                                            $note = App\Models\NoteGroup::find(preg_replace('/[^0-9]/', '', $intervention->reason));
                                        @endphp
                                    @include('/group_interventions/group_intervention_view_note')
                                        <button type="button" name="viewGroupNote" class="btn btn-info rounded-pill" data-toggle="modal" data-target="{{"#viewGroupNote" . preg_replace('/[^0-9]/', '', $intervention->reason)}}">Group Note</button>
                                    @else
                                        <div style="overflow-x: hidden; overflow-y:auto;
                                                                   text-overflow: clip;
                                                                   display: -webkit-box;
                                                                   -webkit-line-clamp: 5; /* number of lines to show */
                                                                   -webkit-box-orient: vertical;">
                                            {{$intervention->reason}}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <div style="overflow-x: hidden; overflow-y:auto;
                                                                   text-overflow: clip;
                                                                   display: -webkit-box;
                                                                   -webkit-line-clamp: 5; /* number of lines to show */
                                                                   -webkit-box-orient: vertical;">
                                        {{$intervention->action}}
                                    </div>
                                </td>

                                <td>@php echo date("F jS", strtotime($intervention->start_day)); @endphp</td>

                                <td>@php echo date("F jS", strtotime($intervention->end_day)); @endphp</td>

                                <form>
                                    @csrf
                                    <input type="hidden" name="_method" value="POST">
                                    <td>
                                        @if($intervention->status == 1)
                                            <button class="btn btn-outline-success rounded-pill" type="button" name="update"  data-toggle="modal" data-target="{{"#statusGroupIntervention" . $intervention->id}}" >Active</button>
                                        @elseif($intervention->status == 2)
                                            <button class="btn btn-outline-info rounded-pill"  type="button" name="update"  data-toggle="modal" data-target="{{"#statusGroupIntervention" . $intervention->id}}">Extended</button>
                                        @elseif($intervention->status == 3)
                                            <button class="btn btn-outline-danger rounded-pill"  type="button" name="update"  data-toggle="modal" data-target="{{"#statusGroupIntervention" . $intervention->id}}">
                                                <span>Closed</span>
                                                <br>
                                                <span>Unsolved</span></button>
                                        @else
                                            <button class="btn btn-outline-secondary rounded-pill" type="button" name="update"  data-toggle="modal" data-target="{{"#statusGroupIntervention" . $intervention->id}}">
                                                <span>Closed</span>
                                                <br>
                                                <span>Solved</span></button>
                                        @endif
                                    </td>
                                </form>

                                <form>
                                    @csrf
                                    <input type="hidden" name="_method" value="POST">
                                    <td align="right">

                                        @if ($intervention->visible_ta == 1)
                                            <i title="Visible to TAs" style="font-size:24px; vertical-align: middle" class="fa">&#xf046;</i>
                                        @else
                                            <i title="Not Visible to TAs" style="font-size:24px; vertical-align: middle" class="fa">&#xf147;</i>
                                        @endif

                                        <button type="button" name="update" class="btn btn-info " value="1"  data-toggle="modal" data-target="{{"#editGroupIntervention" . $intervention->id}}">Edit</button>
                                        <button type="button" name="update" class="btn btn-dark" value="2" data-toggle="modal" data-target="{{"#deleteGroupIntervention" . $intervention->id}}"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                            </svg></button>
                                    </td>
                                </form>

                            </tr>

                            @include ('/group_interventions/group_intervention_edit_modal')
                            @include ('/group_interventions/group_intervention_delete_modal')
                            @include ('/group_interventions/status_modal')
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
