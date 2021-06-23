<div class="modal fade" id="{{"createGroupInterventionNote" . $note->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" align="center"><b>Create Group Intervention</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>

            </div>


            <div class="modal-body">
                <form id="{{"createGroupInterventionNote" . $note->id}}" method="post" value = "<?php echo csrf_token(); ?>" action="{{action('App\Http\Controllers\GroupInterventionsController@createGroupInterventionNote',$note->id)}}">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <div class="box-body">

                        <div class="form-group">
                            <label for="group">Group</label>
                            <h4>{{$group->group_name}}</h4>
                        </div>

                        <div class="form-group">
                            <label for="editReason">Reason</label>
                            @php
                                if($note->problem_signal == 2)
                                $signal = "Warning";
                                elseif ($note->problem_signal == 3)
                                $signal = "Problematic";
                            @endphp
                            <h4>The "{{$signal}}" note from week {{$note->week}}:</h4>
                            <p>{{$note->note}}</p>
                        </div>


                        <div class="form-group">
                            <label for="createAction">Action</label>
                            <textarea type="text" class="form-control" id="createGroupAction" name="createGroupAction" rows="4" placeholder="..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="createStart">Starting</label>
                            <input type='date' class="form-control" id='{{"createStartGroupNote" . $note->id}}' name="{{"createStartGroupNote" . $note->id}}" value="" required/>
                        </div>


                        <div class="form-group">
                            <label for="createEnd">Ending</label>
                            <input type='date'  data-date-format="DD-MM-YYYY" class="form-control" id='{{"createEndGroupNote" . $note->id}}' name="{{"createEndGroupNote" . $note->id}}" value="" required/>
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
