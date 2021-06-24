<div class="modal fade" id="{{"updateEntry" . $rubricEntry->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="align-content: center">Update rubric entry</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="box-body">
                    <div class="form-group">
                        <form action = "/rubricEntryUpdate" method = "post" class="form-group" style="width:70%; margin-left:15%;" action="/action_page.php">
                            <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
                            <input type = "hidden" name = "id" value ="{{$rubricEntry->id}}">
                            @method('PUT')
                            <label>New Description</label>
                            <input type="text" class="form-control" placeholder="{{$rubricEntry->description}}" name="description">

                            <button type="submit"  value = "Add" class="btn btn-primary">Submit update</button>
                        </form>
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
