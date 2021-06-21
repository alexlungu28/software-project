    <div class="col-lg-6 col-md-12">
        <div class="card" >
            <div class="card-header card-header-primary">
                <h4 class="card-title">Group problem cases without interventions</h4>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="profile">
                        <table class="table" style="table-layout:fixed;">
                            <thead class="text-primary">
                            <th style="width:10%">Week</th>
                            <th style="width:40%">Note</th>
                            <th style="width:20%">Signal</th>
                            <th style="width:30%"></th>
                            </thead>
                            <tbody>
                            <!--
                            $groupNotesNoIntervention are passed through the GroupController.
                            it contains the group notes that do not have interventions related to them.
                             -->
                            @foreach($groupNotesNoInterventions as $note)
                                @if($note->problem_signal >= 2)
                                    <tr>
                                        <td>
                                            {{$note->week}}
                                        </td>
                                        <td >
                                            <div style="overflow-x: hidden; overflow-y:auto;
                                                                   text-overflow: clip;
                                                                   display: -webkit-box;
                                                                   -webkit-line-clamp: 5; /* number of lines to show */
                                                                   -webkit-box-orient: vertical;">
                                                {{$note->note}}
                                            </div>
                                        </td>
                                        <td>
                                            @if($note->problem_signal == 2)
                                                <button title="Warning!" class="btn btn-squared-default btn-warning">
                                                    <br />
                                                    <br />
                                                </button>
                                            @else
                                                <button title="Problematic!"  class="btn btn-squared-default btn-danger">
                                                    <br />
                                                    <br />
                                                </button>
                                            @endif

                                        </td>
                                        <td>
                                            @include ('/group_interventions/create_note_based_modal')
                                            <button class="btn btn-primary btn-sm rounded-0" type="button" name="update"  data-toggle="modal" data-target="{{"#createGroupInterventionNote" . $note->id}}">
                                                <span>Create</span>
                                                <br>
                                                <span>Intervention</span>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
