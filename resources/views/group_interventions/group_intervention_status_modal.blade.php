<div class="modal fade" id="{{"statusGroupIntervention" . $intervention->id}}">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" align="center"><b>Status of Intervention #{{$intervention->id}}</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                    <div class="box-body">

                        <div class="form-group">

                            <label>Group</label>
                            <h4 ><b>{{App\Models\Group::find($intervention->group_id)->group_name}}</b></h4>
                        </div>

                        <div class="form-group">
                            <label>Reason</label>

                            @if(preg_match("/^(groupNote)\d+$/i", $intervention->reason))
                                @php
                                    $note = App\Models\Note::find(preg_replace('/[^0-9]/', '', $intervention->reason));
                                @endphp

                                @include('/group_interventions/group_intervention_view_note_from_status_modal')
                            @else

                                    <div style="overflow-x: hidden; overflow-y:auto;
                                                                   text-overflow: clip;
                                                                   display: -webkit-box;
                                                                   -webkit-line-clamp: 5; /* number of lines to show */
                                                                   -webkit-box-orient: vertical;">
                                        {{$intervention->reason}}
                                    </div>


                            @endif
                        </div>

                        <div class="form-group">
                            <label>Action</label>

                                <div style="overflow-x: hidden; overflow-y:auto;
                                                                   text-overflow: clip;
                                                                   display: -webkit-box;
                                                                   -webkit-line-clamp: 5; /* number of lines to show */
                                                                   -webkit-box-orient: vertical;">
                                    {{$intervention->action}}
                                </div>


                        <div class="form-group">
                            <label>Date</label>
                            <h4>@php echo date("F jS", strtotime($intervention->start_day)); @endphp {{" - "}} @php echo date("F jS", strtotime($intervention->end_day)); @endphp </h4>
                        </div>

                        <div class="form-group">
                            <label>Current Status</label>

                            @if($intervention->status == 1)
                                <button class="btn btn-success rounded-pill" disabled >Active</button>
                            @elseif($intervention->status == 2)
                                <button class="btn btn-info rounded-pill" disabled >Extended</button>
                            @elseif($intervention->status == 3)
                                <button class="btn btn-danger rounded-pill" disabled >Closed - Unsolved</button>
                            @else
                                <button class="btn btn-outline-secondary rounded-pill" disabled> Closed - Solved</button>
                            @endif
                        </div>


                        <div class="form-group">
                            <label for="note">Status Note</label>
                            <h4>
                                <div style="overflow-x: hidden; overflow-y:auto;
                                                                   text-overflow: clip;
                                                                   display: -webkit-box;
                                                                   -webkit-line-clamp: 5; /* number of lines to show */
                                                                   -webkit-box-orient: vertical;">
                                    {{$intervention->status_note}}
                                </div>
                                </h4>
                        </div>

                        <div class="form-group">
                                    <ul class="nav nav-pills " id="pills-tab" role="tablist">

                                        <li  style="margin:0 2px" class="nav-item">
                                            <button @if($intervention->status == 1 || $intervention->status == 2)
                                                    style="display:none"
                                                    @endif
                                                    type="button" class="btn btn-outline-success btn-block" id="pills-active-group-tab" data-toggle="pill" href={{"#activeGroup" . $intervention->id}} role="tab" aria-controls="activeGroup" aria-selected="false">Active</button>
                                        </li>

                                        <li style="margin:0 2px" class="nav-item">
                                            <button @if($intervention->status == 3 || $intervention->status == 4)
                                                    style="display:none"
                                                    @endif
                                                    type="button" class="btn btn-outline-info btn-block" id="pills-extend-group-tab" data-toggle="pill" href={{"#extendGroup" . $intervention->id}} role="tab" aria-controls="extendGroup" aria-selected="false">Extend</button>
                                        </li>

                                        <li  style="margin:0 2px" class="nav-item">
                                            <button @if($intervention->status == 3)
                                                    style="display:none"
                                                    @endif
                                                    type="button" class="btn btn-outline-danger btn-block" id="pills-unsolved-group-tab" data-toggle="pill" href={{"#unsolvedGroup" . $intervention->id}} role="tab" aria-controls="unsolvedGroup" aria-selected="false">Close - Unsolved</button>
                                        </li>

                                        <li  style="margin:0 2px" class="nav-item">
                                            <button @if($intervention->status == 4)
                                                    style="display:none"
                                                    @endif
                                                    type="button" class="btn btn-outline-secondary btn-block" id="pills-solved-group-tab" data-toggle="pill" href={{"#solvedGroup" . $intervention->id}} role="tab" aria-controls="solved" aria-selected="false">Close- Solved</button>
                                        </li>
                                    </ul>

                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show" id={{"activeGroup" . $intervention->id}} role="tabpanel" aria-labelledby="pills-active-group-tab">
                                    <form id={{"statusGroupIntervention" . $intervention->id}} method="post" value = "<?php echo csrf_token(); ?>" action="{{action('App\Http\Controllers\GroupInterventionsController@statusActive',$intervention->id)}}">
                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                        <label for="active_note">Note</label>
                                        <textarea type="text" class="form-control" id="active_note" name="active_note" rows="4" value="">{{$intervention->status_note}}</textarea>
                                        <button type="submit" class="btn btn-success">Change Status</button>
                                    </form>
                                </div>

                                <div class="tab-pane fade show" id={{"extendGroup" . $intervention->id}} role="tabpanel" aria-labelledby="pills-extend-group-tab">
                                    <form id={{"statusIntervention" . $intervention->id}} method="post" value = "<?php echo csrf_token(); ?>" action="{{action('App\Http\Controllers\GroupInterventionsController@statusExtend',$intervention->id)}}" required>
                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                        <label for="solvedNote">Note</label>
                                        <input type='date'  data-date-format="DD-MM-YYYY" class="form-control" id='{{"extend_end" . $intervention->id}}' name="{{"extend_end" . $intervention->id}}" value="" required/>
                                        <textarea type="text" class="form-control" id="extend_note" name="extend_note" rows="4" value="">The deadline of this intervention was extended, the old deadline being on @php echo date("F jS", strtotime($intervention->end_day));@endphp. {{"\n"}}{{$intervention->status_note . "\n"}}</textarea>

                                        <button type="submit" class="btn btn-info">Change Status</button>
                                    </form>
                                </div>

                                <div class="tab-pane fade show" id={{"unsolved" . $intervention->id}} role="tabpanel" aria-labelledby="pills-unsolved-tab">
                                    <form id={{"statusIntervention" . $intervention->id}} method="post" value = "<?php echo csrf_token(); ?>" action="{{action('App\Http\Controllers\InterventionsController@statusUnsolved',$intervention->id)}}">
                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                                        <label for="solvedNote">Note</label>
                                        <textarea type="text" class="form-control" id="unsolved_note" name="unsolved_note" rows="4" value="">{{$intervention->status_note}}</textarea>
                                        <button type="submit" class="btn btn-danger">Change Status</button>
                                    </form>
                                </div>

                                <div class="tab-pane fade show" id={{"solved" . $intervention->id}} role="tabpanel" aria-labelledby="pills-solved-tab">
                                    <form id={{"statusIntervention" . $intervention->id}} method="post" value = "<?php echo csrf_token(); ?>" action="{{action('App\Http\Controllers\InterventionsController@statusSolved',$intervention->id)}}">
                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                                        <label for="solved_note">Note</label>
                                        <textarea type="text" class="form-control" id="solved_note" name="solved_note" rows="4" value="">{{$intervention->status_note}}</textarea>

                                        <button type="submit" class="btn btn-outline-secondary">Change Status</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="close" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <script type="text/javascript">
                            $(window).on('load', function (e) {
                                $('#close').on('click', function (e) {
                                    $("#div").load(" #div > *");
                                });
                            });
                        </script>
                    </div>
            </div>
        </div>
    </div>
</div>
