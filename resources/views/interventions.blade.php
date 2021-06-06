@extends('layouts.app', ['activePage' => 'interventions', 'titlePage' => __('Interventions')])
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">



</head>
@section('content')
    <div class="content">
        <div class="container-fluid">

            <div class="table-responsive">

                <h3>Interventions</h3>
                <button type="button" name="createIntervention" class="btn btn-danger rounded-pill" value="2" data-toggle="modal" data-target="{{"#createIntervention" . $edition_id}}">Create Intervention</button>





                <div class="card">

                    <div class="card-block table-border-style">
                        <div class="table-responsive">

                            <table class="table table-hover" style="table-layout:fixed;">
                                <thead>
                                <tr>
                                    <th style="width:15%">Name</th>
                                    <th style="width:10%">Group</th>
                                    <th style="width:20%">Reason</th>
                                    <th style="width:20%">Action</th>
                                    <th style="width:10%">Starting</th>
                                    <th style="width:10%">Ending</th>
                                    <th style="width:25%"></th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($interventions as $intervention)
                                    <tr>

                                        <td>{{App\Models\User::find($intervention->user_id)->first_name . " " . App\Models\User::find($intervention->user_id)->last_name }}</td>
                                        <td>{{App\Models\Group::find($intervention->group_id)->group_name}}</td>

                                        <td>{{$intervention->reason}}</td>

                                        <td>{{$intervention->action}}
                                          </td>

                                        <td>{{$intervention->start_day}}</td>
                                        <td>{{$intervention->end_day}}</td>

                                        <form>
                                            @csrf
                                            <input type="hidden" name="_method" value="POST">
                                            <td>
                                                <button type="button" name="update" class="btn btn-info " value="1"  data-toggle="modal" data-target="{{"#editIntervention" . $intervention->id}}">Edit</button>
                                                <button type="button" name="update" class="btn btn-danger rounded-pill" value="2" data-toggle="modal" data-target="{{"#deleteIntervention" . $intervention->id}}">Delete</button>

                                            </td>
                                        </form>


                                    </tr>
                                    <div class="modal fade" id="{{"editIntervention" . $intervention->id}}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" align="center"><b>Edit Intervention #{{$intervention->id}}</b></h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span></button>

                                                </div>


                                                <div class="modal-body">
                                                    <form id={{"intervention" . $intervention->id}} method="post" value = "<?php echo csrf_token(); ?>" action="{{action('App\Http\Controllers\InterventionsController@editIntervention',$intervention->id)}}">
                                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                                        <div class="box-body">
                                                            <div class="form-group">
                                                                <label for="name">Name</label>
                                                                <h4 ><b>{{App\Models\User::find($intervention->user_id)->first_name . " " . App\Models\User::find($intervention->user_id)->last_name }}</b></h4>
                                                                <label for="group">Group</label>
                                                                <h4 ><b>{{App\Models\Group::find($intervention->group_id)->group_name}}</b></h4>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="editReason">Reason</label>
                                                                <textarea type="text" class="form-control" id="editReason" name="editReason" rows="4" value="">{{$intervention->reason}}</textarea>
                                                            </div>


                                                            <div class="form-group">
                                                                <label for="editAction">Action</label>
                                                                <textarea type="text" class="form-control" id="editAction" name="editAction" rows="4" value="">{{$intervention->action}}</textarea>
                                                            </div>

                                                                <div class="form-group">
                                                                        <label for="editStart">Starting</label>
                                                                        <input type='text' class="form-control" id='{{"editStart" . $intervention->id}}' name="{{"editStart" . $intervention->id}}" value="{{$intervention->start_day}}" />
                                                                    </div>
                                                                    <script type="text/javascript">
                                                                        $(function () {
                                                                            moment.locale('en', {
                                                                                week: { dow: 1 } // Monday is the first day of the week
                                                                            });
                                                                            $('{{"#editStart" . $intervention->id}}').datetimepicker({
                                                                                format: 'YYYY-MM-DD'
                                                                            });
                                                                        });
                                                                    </script>


                                                            <div class="form-group">
                                                                <label for="editEnd">Ending</label>
                                                                <input type='text' class="form-control" form={{"intervention" . $intervention->id}} id='{{"editEnd" . $intervention->id}}' name="{{"editEnd" . $intervention->id}}" value="{{$intervention->end_day}}"/>
                                                            </div>
                                                            <script type="text/javascript">
                                                                $(function () {
                                                                    moment.locale('en', {
                                                                        week: { dow: 1 } // Monday is the first day of the week
                                                                    });
                                                                    $('{{"#editEnd" . $intervention->id}}').datetimepicker({
                                                                        format: 'YYYY-MM-DD'
                                                                    });
                                                                });
                                                            </script>

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



                                    <div class="modal fade" id="{{"deleteIntervention" . $intervention->id}}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" align="center"><b>Are you sure you want to delete this intervention?</b></h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span></button>

                                                </div>



                                                <div class="modal-body">
                                                    <form id={{"deleteIntervention" . $intervention->id}} method="post" value = "<?php echo csrf_token(); ?>" action="{{action('App\Http\Controllers\InterventionsController@deleteIntervention',$intervention->id)}}">
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







                                @endforeach



                                </tbody>
                            </table>

                            <div class="modal fade" id="{{"createIntervention" . $edition_id}}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" align="center"><b>Create Intervention</b></h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>

                                        </div>


                                        <div class="modal-body">
                                            <form id={{"createIntervention"}} method="post" value = "<?php echo csrf_token(); ?>" action="{{action('App\Http\Controllers\InterventionsController@createIntervention',$edition_id)}}">
                                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                                <div class="box-body">
                                                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

                                                    <!-- (Optional) Latest compiled and minified JavaScript translation files -->
                                                    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>




                                                    <div class="form-group">
                                                        <label for="name">Name</label>
                                                        <select class="selectpicker" data-live-search="true" name="createUser" id='createUser'>
                                                            @foreach(App\Models\CourseEditionUser::where('course_edition_id', '=', $edition_id)->where('role','=','student')->get() as $user)
                                                                <option value="{{$user->user_id}}" >{{App\Models\User::find($user->user_id)->first_name . " " . App\Models\User::find($user->user_id)->last_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="editReason">Reason</label>
                                                        <textarea type="text" class="form-control" id="createReason" name="createReason" rows="4" placeholder="..."></textarea>
                                                    </div>


                                                    <div class="form-group">
                                                        <label for="createAction">Action</label>
                                                        <textarea type="text" class="form-control" id="createAction" name="createAction" rows="4" placeholder="..."></textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="createStart">Starting</label>
                                                        <input type='text' class="form-control" id='{{"createStart" . $edition_id}}' name="{{"createStart" . $edition_id}}" value="" />
                                                    </div>
                                                    <script type="text/javascript">
                                                        $(function () {
                                                            moment.locale('en', {
                                                                week: { dow: 1 } // Monday is the first day of the week
                                                            });
                                                            $('{{"#createStart" . $edition_id}}').datetimepicker({
                                                                format: 'YYYY-MM-DD'
                                                            });
                                                        });
                                                    </script>

                                                    <div class="form-group">
                                                        <label for="createEnd">Ending</label>
                                                        <input type='text' class="form-control" id='{{"createEnd" . $edition_id}}' name="{{"createEnd" . $edition_id}}" value="" />
                                                    </div>
                                                    <script type="text/javascript">
                                                        $(function () {
                                                            moment.locale('en', {
                                                                week: { dow: 1 } // Monday is the first day of the week
                                                            });
                                                            $('{{"#createEnd" . $edition_id}}').datetimepicker({
                                                                format: 'YYYY-MM-DD'
                                                            });
                                                        });


                                                    </script>

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






                        </div>
                    </div>
                </div></div></div></div></div>



@endsection
