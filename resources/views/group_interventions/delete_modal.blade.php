<div class="modal fade" id="{{"deleteGroupIntervention" . $intervention->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" align="center"><b>Are you sure you want to delete this intervention?</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>

            </div>

            <div class="modal-body">
                <form id={{"deleteGroupIntervention" . $intervention->id}} method="post" value = "<?php echo csrf_token(); ?>" action="{{action('App\Http\Controllers\GroupInterventionsController@deleteGroupIntervention',$intervention->id)}}">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <div class="modal-footer">
                        <button type="button" id="close" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                        <script type="text/javascript">
                            $(window).on('load', function (e) {
                                $('#close').on('click', function (e) {
                                    $("#div").load(" #div > *");
                                });
                            });
                        </script>

                        <button type="submit" class="btn btn-primary">Delete!</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
