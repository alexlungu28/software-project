<div class="tab-pane fade show active" id="attendanceTab" role="tabpanel" aria-labelledby="pills-attendance-tab">


    <div class="table-responsive">

        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>



        <div class="table-responsive">

            <div class="col-xl-6">
                <div class="card">

                        <div class="card-header card-header-primary">
                            <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
                            <h4 class="card-title ">Attendance</h4>
                        </div>


                        <div class="table-responsive">

                            <table class="table table-hover"  style="table-layout:fixed; overflow:auto">
                                <thead>
                                <tr>
                                    <th style="width: 15%">Week</th>
                                    <th style="width: 35%">Status</th>
                                    <th style="width: 50%">Reason (if late or absent)</th>

                                </tr>
                                </thead>
                                <tbody>

                                @php
                                    $attendancesPresent = $attendances->where('status', '=', '1')->count();
                                @endphp

                                @foreach($attendances as $at)

                                    <tr>

                                        <td>
                                            {{$at->week}}
                                        </td>

                                        <td>
                                            @if($at->status == 1)
                                                <button class="btn btn-info rounded-pill" cursor="default" >Present</button>
                                            @elseif($at->status == 2)
                                                <button class="btn btn-warning rounded-pill" cursor="default" >Late</button>
                                            @elseif($at->status == 3)
                                                <button class="btn btn-danger rounded-pill" cursor="default" >Absent</button>
                                            @else
                                                {{" "}}
                                            @endif
                                        </td>

                                        <td>{{$at->reason}}</td>

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

