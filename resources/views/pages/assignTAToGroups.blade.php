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
        </script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

        <script type="text/javascript">

            $(document).ready(function() {

                $('select').selectpicker();

            });
        </script>
        <style>
            .bootstrap-select > .dropdown-toggle.bs-placeholder, .bootstrap-select > .dropdown-toggle.bs-placeholder:active, .bootstrap-select > .dropdown-toggle.bs-placeholder:focus, .bootstrap-select > .dropdown-toggle.bs-placeholder:hover {
                color: #000;
            }
            .btn, .btn.btn-default {
                color: #000000;
                background-color: #ffffff;
                border-color: #999999;
                box-shadow: 0 2px 2px 0 rgba(153, 153, 153, 0.14), 0 3px 1px -2px rgba(153, 153, 153, 0.2), 0 1px 5px 0 rgba(153, 153, 153, 0.12);
            }
            .btn:focus, .btn.focus, .btn:hover, .btn.btn-default:focus, .btn.btn-default.focus, .btn.btn-default:hover {
                color: #fff;
                background-color: #e8e5e5;
                border-color: #ded8d8;
            }
        </style>
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
                                    <form
                                        id="{{'bestForm' . $loop->index}}"
                                        method="post"
                                        action="{{route('assignTaToGroupsStore',$edition_id)}}">
                                        @csrf
                                    <tr>
                                        <input type="hidden" class="form-control" name='user_id' . {{$loop->index}} value={{$user->user_id}}>
                                        <input type="hidden" class="form-control" name='edition_id' . {{$loop->index}} value={{$edition_id}}>
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
                                                <label>Select Groups:</label>
                                                <select form="{{'bestForm' . $loop->index}}" class="selectpicker"  name="groups[]" data-live-search="true" multiple>
                                                    @foreach($groups as $group)
                                                        @if(DB::table('group_user')->where('user_id', '=', $user->user_id)->where('group_id', '=', $group->id)->exists() )
                                                            <option value="{{$group->id}}" selected>{{$group->group_name}}</option>
                                                        @else
                                                            <option value="{{$group->id}}" >{{$group->group_name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                        </td>
                                        <td>
                                            <button
                                                type="submit"
                                                class="btn btn-primary">Assign to Groups</button>
                                        </td>
                                    </tr>
                                    </form>
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
