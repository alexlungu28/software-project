<div class="modal fade" id="{{"editGroupIntervention" . $intervention->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" align="center"><b>Edit Group Intervention #{{$intervention->id}}</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>

            </div>



            <div class="modal-body">
                <form id="{{"editGroupIntervention" . $intervention->id}}" method="post" value = "<?php echo csrf_token(); ?>" action="{{action('App\Http\Controllers\GroupInterventionsController@editGroupIntervention',$intervention->id)}}">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <div class="box-body">

                        <div class="form-group">
                            <label for="visibility">Visible to TAs </label>

                            <input type="checkbox"  onclick="$(this).next().val(this.checked?1:0)" name="{{"editGroupVisibility" . $intervention->id}}" style="vertical-align: middle" data-toggle="toggle"
                                    @if ($intervention->visible_ta)
                                        checked
                                    @endif
                            value="1">
                            <input type="hidden"  onclick="$(this).next().val(this.checked?1:0)" name="{{"editGroupVisibility" . $intervention->id}}" style="vertical-align: middle" data-toggle="toggle"

                                   value="0">
                        </div>

                        <div class="form-group">
                            <label for="group">Group</label>
                            <h5 ><b>{{App\Models\Group::find($intervention->group_id)->group_name}}</b></h5>
                        </div>
                        <div class="form-group">
                            <label for="editReason">Reason</label>

                            @if(preg_match("/^(groupNote)\d+$/i", $intervention->reason))
                                @php
                                    $note = App\Models\NoteGroup::find(preg_replace('/[^0-9]/', '', $intervention->reason));
                                    //return dd($note);
                                @endphp


                                @include('/group_interventions/group_intervention_view_note_from_edit_modal')
                            @else

                                    <div style="overflow-x: hidden; overflow-y:auto;
                                                                   text-overflow: clip;
                                                                   display: -webkit-box;
                                                                   -webkit-line-clamp: 5; /* number of lines to show */
                                                                   -webkit-box-orient: vertical;">
                                        <textarea type="text" class="form-control" id="editGroupReason" name="editGroupReason" rows="4" value="">{{$intervention->reason}}</textarea>
                                    </div>

                            @endif
                        </div>


                        <div class="form-group">
                            <label for="editGroupAction">Action</label>
                            <textarea type="text" class="form-control" id="editGroupAction" name="editGroupAction" rows="4" value="">{{$intervention->action}}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="editGroupStart">Starting</label>
                            <input type='date' class="form-control" id='{{"editGroupStart" . $intervention->id}}' name="{{"editGroupStart" . $intervention->id}}" value="{{$intervention->start_day}}" required/>
                        </div>



                        <div class="form-group">
                            <label for="editEnd">Ending</label>
                            <input type='date' class="form-control" id='{{"editGroupEnd" . $intervention->id}}' name="{{"editGroupEnd" . $intervention->id}}" value="{{$intervention->end_day}}" required/>
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

                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
