<div class="col-lg-6 col-md-12">
    <div class="card" >
        <div class="card-header card-header-primary">
            <h4 class="card-title">Interventions</h4>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active" id="profile">
                    <table class="table"  style="table-layout:fixed;">
                        <thead class="text-primary">
                        <th>Name</th>
                        <th>Reason</th>

                        <th>Ending</th>
                        <th></th>
                        </thead>
                        <tbody>
                        @foreach($group->groupIndividualInterventions->sortBy('end_day')->sortBy('status') as $intervention)

                            <tr>
                                <td>{{App\Models\User::find($intervention->user_id)->first_name . " " . App\Models\User::find($intervention->user_id)->last_name }}</td>


                                <td>

                                    @if(preg_match("/^(note)\d+$/i", $intervention->reason))
                                        @php
                                            $note = App\Models\Note::find(preg_replace('/[^0-9]/', '', $intervention->reason));
                                        @endphp

                                        @include('/interventions/intervention_view_note')
                                        <button type="button" name="viewNote" class="btn btn-info rounded-pill" data-toggle="modal" data-target="{{"#viewNote" . preg_replace('/[^0-9]/', '', $intervention->reason)}}">Note</button>
                                    @else
                                        <div style="overflow-x: hidden; overflow-y:auto;
                                                                   text-overflow: clip;
                                                                   display: -webkit-box;
                                                                   -webkit-line-clamp: 5; /* number of lines to show */
                                                                   -webkit-box-orient: vertical;">
                                        {{$intervention->reason}}
                                        </div>
                                    @endif
                                </td>





                                <td>@php echo date("F jS", strtotime($intervention->end_day)); @endphp</td>

                                <td>
                                    @if($intervention->status == 1)
                                        <button class="btn btn-outline-success rounded-pill" type="button" name="update"   >Active</button>
                                    @elseif($intervention->status == 2)
                                        <button class="btn btn-outline-info rounded-pill"  type="button" name="update"  >Extended</button>
                                    @elseif($intervention->status == 3)
                                        <button class="btn btn-outline-danger rounded-pill"  type="button" name="update"  >
                                            <span>Closed</span>
                                            <br>
                                            <span>Unsolved</span></button>
                                    @else
                                        <button class="btn btn-outline-secondary rounded-pill" type="button" name="update"  >
                                            <span>Closed</span>
                                            <br>
                                            <span>Solved</span></button>
                                    @endif

                                </td>

                            </tr>

                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
