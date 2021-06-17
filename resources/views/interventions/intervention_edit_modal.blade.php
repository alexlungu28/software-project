<div class="modal fade" id="{{"editIntervention" . $intervention->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" align="center"><b>Edit Intervention #{{$intervention->id}}</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>

            </div>


            <div class="modal-body">
                <form id={{"editIntervention" . $intervention->id}} method="post" value = "<?php echo csrf_token(); ?>" action="{{action('App\Http\Controllers\InterventionsController@editIntervention',$intervention->id)}}">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <div class="box-body">

                        <div class="form-group">
                            <label for="visibility">Visible to TAs </label>

                            <input type="checkbox"  onclick="$(this).next().val(this.checked?1:0)" name="{{"editVisibility" . $intervention->id}}" style="vertical-align: middle" data-toggle="toggle"
                                    @if ($intervention->visible_ta)
                                        checked
                                    @endif
                            value="1">
                            <input type="hidden"  onclick="$(this).next().val(this.checked?1:0)" name="{{"editVisibility" . $intervention->id}}" style="vertical-align: middle" data-toggle="toggle"

                                   value="0">
                        </div>

                        <div class="form-group">
                            <label for="name">Name</label>
                            <h5 ><b>{{App\Models\User::find($intervention->user_id)->first_name . " " . App\Models\User::find($intervention->user_id)->last_name }}</b></h5>
                            <label for="group">Group</label>
                            <h5 ><b>{{App\Models\Group::find($intervention->group_id)->group_name}}</b></h5>
                        </div>
                        <div class="form-group">
                            <label for="editReason">Reason</label>

                            @if(preg_match("/^(note)\d+$/i", $intervention->reason))
                                @php
                                    $note = App\Models\Note::find(preg_replace('/[^0-9]/', '', $intervention->reason));
                                @endphp

                                @include('/interventions/intervention_view_note_from_edit_modal')
                            @else

                                    <div style="overflow-x: hidden; overflow-y:auto;
                                                                   text-overflow: clip;
                                                                   display: -webkit-box;
                                                                   -webkit-line-clamp: 5; /* number of lines to show */
                                                                   -webkit-box-orient: vertical;">
                                        <textarea type="text" class="form-control" id="editReason" name="editReason" rows="4" value="">{{$intervention->reason}}</textarea>
                                    </div>

                            @endif
                        </div>


                        <div class="form-group">
                            <label for="editAction">Action</label>
                            <textarea type="text" class="form-control" id="editAction" name="editAction" rows="4" value="">{{$intervention->action}}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="editStart">Starting</label>
                            <input type='date' class="form-control" id='{{"editStart" . $intervention->id}}' name="{{"editStart" . $intervention->id}}" value="{{$intervention->start_day}}" required/>
                        </div>



                        <div class="form-group">
                            <label for="editEnd">Ending</label>
                            <input type='date' class="form-control" id='{{"editEnd" . $intervention->id}}' name="{{"editEnd" . $intervention->id}}" value="{{$intervention->end_day}}" required/>
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
