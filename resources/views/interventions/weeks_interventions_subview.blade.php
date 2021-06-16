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
                        <th style="width:25%">Name</th>
                        <th style="width:35%">Action</th>
                        <th style="width:20%">Ending</th>
                        <th style="width:20%">View / Change Status</th>
                        </thead>
                        <tbody>
                        @foreach($group->groupIndividualInterventions->sortBy('end_day')->sortBy('status') as $intervention)

                            <tr>
                                <td>{{App\Models\User::find($intervention->user_id)->first_name . " " . App\Models\User::find($intervention->user_id)->last_name }}</td>



                                <td>       <div style="overflow-x: hidden; overflow-y:auto;
                                                                   text-overflow: clip;
                                                                   display: -webkit-box;
                                                                   -webkit-line-clamp: 5; /* number of lines to show */
                                                                   -webkit-box-orient: vertical;">
                                        {{$intervention->action}}
                                    </div>
                                </td>



                                <td>@php echo date("F jS", strtotime($intervention->end_day)); @endphp</td>

                                <td>
                                    @if($intervention->status == 1)
                                        <button class="btn btn-outline-success rounded-pill" type="button" name="update"  data-toggle="modal" data-target="{{"#statusIntervention" . $intervention->id}}" >Active</button>
                                    @elseif($intervention->status == 2)
                                        <button class="btn btn-outline-info rounded-pill"  type="button" name="update"  data-toggle="modal" data-target="{{"#statusIntervention" . $intervention->id}}">Extended</button>
                                    @elseif($intervention->status == 3)
                                        <button class="btn btn-outline-danger rounded-pill"  type="button" name="update"  data-toggle="modal" data-target="{{"#statusIntervention" . $intervention->id}}">  <span>Closed</span>
                                            <br>
                                            <span>Unsolved</span></button>
                                    @else
                                        <button class="btn btn-outline-secondary rounded-pill" type="button" name="update"  data-toggle="modal" data-target="{{"#statusIntervention" . $intervention->id}}">  <span>Closed</span>
                                            <br>
                                            <span>Solved</span></button>
                                    @endif

                                </td>

                            </tr>
                            @include ('/interventions/intervention_status_modal')
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
