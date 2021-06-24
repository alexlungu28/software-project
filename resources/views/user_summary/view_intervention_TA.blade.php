<div class="modal fade" id="{{"viewInterventionTASummary" . $intervention->id}}">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" align="center"><b>Intervention #{{$intervention->id}}</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="box-body">

                    <div class="form-group">
                        <label>Name</label>
                        <h4 ><b>{{App\Models\User::find($intervention->user_id)->first_name . " " . App\Models\User::find($intervention->user_id)->last_name }}</b></h4>

                        <label>Group</label>
                        <h4 ><b>{{App\Models\Group::find($intervention->group_id)->group_name}}</b></h4>
                    </div>

                    <div class="form-group">
                        <label>Reason</label>

                        @if(preg_match("/^(note)\d+$/i", $intervention->reason))
                            @php
                                $note = App\Models\Note::find(preg_replace('/[^0-9]/', '', $intervention->reason));
                            @endphp

                            <div style="overflow-x: hidden; overflow-y:auto;
                                                                   text-overflow: clip;
                                                                   display: -webkit-box;
                                                                   -webkit-line-clamp: 5; /* number of lines to show */
                                                                   -webkit-box-orient: vertical;">{{"The problematic note from week " . $note->week . ": " .
                                $note->note}}
                            </div>

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
                                <div style="overflow-x: hidden; overflow-y:auto;
                                                                   text-overflow: clip;
                                                                   display: -webkit-box;
                                                                   -webkit-line-clamp: 5; /* number of lines to show */
                                                                   -webkit-box-orient: vertical;">
                                    {{$intervention->status_note}}
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
</div>
