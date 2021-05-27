@extends('layouts.app', ['activePage' => 'assignTaToGroups', 'titlePage' => __('RubricView')])

@section('content')
    <head>
        <meta charset="utf-8" />
        <title></title>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready( function () {
                $('#table').DataTable();
            } );
            function multipleFunc() {
                document.getElementById("mySelect").multiple = true;
            }
        </script>
    </head>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
                            <h4 class="card-title ">TA/Head TA List</h4>
                        </div>
                        <div class="card-body">
                            <table id ="table" class="table">
                                <thead class=" text-primary">
                                <th>Net ID</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Role</th>
                                <th>Groups</th>
                                </thead>
                                <tbody>
                                @foreach($courseEditionUser as $user)
                                    <tr>
                                        <td>
                                            {{DB::table('users')
                                                    ->where('id', '=', $user->user_id)
                                                    ->value('net_id')}}
                                        </td>
                                        <td>
                                            {{DB::table('users')
                                                    ->where('id', '=', $user->user_id)
                                                    ->value('last_name')}}
                                        </td>
                                        <td>
                                            {{DB::table('users')
                                                    ->where('id', '=', $user->user_id)
                                                    ->value('first_name')}}
                                        </td>
                                        <td>
                                            {{$user->role}}
                                        </td>
                                        <td>
                                            <label for="group-select">Choose groups:</label>
                                            <form
                                                method="post"
                                                action="">
                                                @csrf
                                                <select class="" name="id" id="mySelect">
                                                    @foreach($groups as $group)
                                                        <option>{{$group->group_name}}</option>
                                                    @endforeach
                                                </select>
                                            </form>
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

@endsection
