<div class="tab-pane fade show" id="notesTab" role="tabpanel" aria-labelledby="pills-notes-tab">

<div class="row">

    <div class="col-lg-6 col-md-12">
        <div class="card" >
            <div class="card-header card-header-primary">
                <h4 class="card-title">Individual Notes</h4>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="profile">
                        <table class="table" style="table-layout:fixed;">
                            <thead class="text-primary">
                            <th style="width:20%">Week</th>
                            <th style="width:50%">Note</th>
                            <th style="width:30%">Signal</th>
                            </thead>
                            <tbody>

                            @foreach($notes as $note)
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
                                            @if($note->problem_signal == 1)
                                                <button title="All good!" class="btn btn-squared-default btn-success">
                                                    <br />
                                                    <br />
                                                </button>

                                            @elseif($note->problem_signal == 2)
                                                <button title="Warning!" class="btn btn-squared-default btn-warning">
                                                    <br />
                                                    <br />
                                                </button>
                                            @elseif ($note->problem_signal == 3)
                                                <button title="Problematic!"  class="btn btn-squared-default btn-danger">
                                                    <br />
                                                    <br />
                                                </button>
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



    <div class="col-lg-6 col-md-12">
        <div class="card" >
            <div class="card-header card-header-primary">
                <h4 class="card-title">Group notes</h4>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="profile">
                        <table class="table" style="table-layout:fixed;">
                            <thead class="text-primary">
                            <th style="width:20%">Week</th>
                            <th style="width:50%">Note</th>
                            <th style="width:30%">Signal</th>
                            </thead>
                            <tbody>

                            @foreach($groupNotes as $note)
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
                                        <td>  @if($note->problem_signal == 1)
                                                <button title="All good!" class="btn btn-squared-default btn-success">
                                                    <br />
                                                    <br />
                                                </button>

                                            @elseif ($note->problem_signal == 2)
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

                                    </tr>

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

</div>
