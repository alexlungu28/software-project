<div class="modal fade" id="{{"createGroupIntervention" . $edition_id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" align="center"><b>Create Group Intervention</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>

            </div>


            <div class="modal-body">
                <form id={{"createGroupIntervention" . $edition_id}} method="post" value = "<?php echo csrf_token(); ?>" action="{{action('App\Http\Controllers\GroupInterventionsController@createGroupIntervention',$edition_id)}}">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <div class="box-body">
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

                        <!-- (Optional) Latest compiled and minified JavaScript translation files -->
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>

                        <div class="form-group" >
                            <label for="group">Group</label>
                            <select class="selectpicker" data-live-search="true" name="createGroup" id="createGroup" >

                                @foreach(App\Models\Group::where('course_edition_id', '=', $edition_id)->get() as $group)

                                    <option value="{{$group->id}}" >{{App\Models\Group::find($group->id)->group_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="createGroupReason">Reason</label>
                            <textarea type="text" class="form-control" id="createGroupReason" name="createGroupReason" rows="4" placeholder="..."></textarea>
                        </div>


                        <div class="form-group">
                            <label for="createGroupAction">Action</label>
                            <textarea type="text" class="form-control" id="createGroupAction" name="createGroupAction" rows="4" placeholder="..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="createGroupStart">Starting</label>

                            <input type='date' class="form-control" id='{{"createGroupStart" . $edition_id}}' name="{{"createGroupStart" . $edition_id}}" value="" required />
                        </div>


                        <div class="form-group">
                            <label for="createGroupEnd">Ending</label>
                            <input type='date' class="form-control" id='{{"createGroupEnd" . $edition_id}}' name="{{"createGroupEnd" . $edition_id}}" value="" required/>
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
