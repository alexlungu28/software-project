<div class="modal fade" id="{{"viewNotee" . preg_replace('/[^0-9]/', '', $intervention->reason)}}">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title" align="center"><b>Note #{{$note->id}}</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>

            </div>


            <div class="modal-body">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <div class="box-body">

                        <div class="form-group">
                            <label for="name">Name</label>
                            <h4 ><b>{{App\Models\User::find($note->user_id)->first_name . " " . App\Models\User::find($note->user_id)->last_name }}</b></h4>
                            <label for="name">Group</label>
                            <h4 ><b>{{App\Models\Group::find($note->group_id)->group_name}}</b></h4>
                            <label for="name">Week</label>
                            <h4 ><b>{{$note->week}}</b></h4>
                        </div>

                        <div class="form-group">
                            @if($note->problem_signal == 1)
                                <button class="btn btn-success rounded-pill" cursor="default" >All good!</button>
                            @elseif($note->problem_signal == 2)
                                <button class="btn btn-warning rounded-pill" cursor="default" >Warning!</button>
                            @elseif($note->problem_signal == 3)
                                <button class="btn btn-danger rounded-pill" cursor="default" >Problematic!</button>
                            @else
                                {{$problemSignal = " "}}
                            @endif
                        </div>


                        <div class="form-group">
                            <label for="viewReason">Note</label>
                            <h4>{{$note->note}}</h4>
                        </div>




                    </div>




                    <div class="modal-footer">
                        <button type="button" id="close" class="btn btn-default pull-left" data-dismiss="modal">Close</button>


                    </div>

            </div>
        </div>
    </div>
</div>
