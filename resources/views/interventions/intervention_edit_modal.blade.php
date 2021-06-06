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
                            <label for="name">Name</label>
                            <h4 ><b>{{App\Models\User::find($intervention->user_id)->first_name . " " . App\Models\User::find($intervention->user_id)->last_name }}</b></h4>
                            <label for="group">Group</label>
                            <h4 ><b>{{App\Models\Group::find($intervention->group_id)->group_name}}</b></h4>
                        </div>
                        <div class="form-group">
                            <label for="editReason">Reason</label>
                            <textarea type="text" class="form-control" id="editReason" name="editReason" rows="4" value="">{{$intervention->reason}}</textarea>
                        </div>


                        <div class="form-group">
                            <label for="editAction">Action</label>
                            <textarea type="text" class="form-control" id="editAction" name="editAction" rows="4" value="">{{$intervention->action}}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="editStart">Starting</label>
                            <input type='text' class="form-control" id='{{"editStart" . $intervention->id}}' name="{{"editStart" . $intervention->id}}" value="{{$intervention->start_day}}" />
                        </div>
                        <script type="text/javascript">
                            $(function () {
                                moment.locale('en', {
                                    week: { dow: 1 } // Monday is the first day of the week
                                });
                                $('{{"#editStart" . $intervention->id}}').datetimepicker({
                                    format: 'YYYY-MM-DD'
                                });
                            });
                        </script>


                        <div class="form-group">
                            <label for="editEnd">Ending</label>
                            <input type='text' class="form-control" id='{{"editEnd" . $intervention->id}}' name="{{"editEnd" . $intervention->id}}" value="{{$intervention->end_day}}"/>
                        </div>
                        <script type="text/javascript">
                            $(function () {
                                moment.locale('en', {
                                    week: { dow: 1 } // Monday is the first day of the week
                                });
                                $('{{"#editEnd" . $intervention->id}}').datetimepicker({
                                    format: 'YYYY-MM-DD'
                                });
                            });
                        </script>

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
