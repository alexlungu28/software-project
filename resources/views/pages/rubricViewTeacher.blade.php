@extends('layouts.app', ['activePage' => 'rubrics', 'titlePage' => __('RubricViewTeacher')])

@section('content')
    <head>
        <meta charset="utf-8" />
        <title></title>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script>
        </script>
    </head>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                                <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">

                                <h4 class="card-title ">{{$rubric->name}}</h4>
                        </div>
                        <div class="card-body">
                                <table id ="table" class="table">
                                    <thead class=" text-primary">
                                    <th></th>
                                    @foreach($rubricColumnEntries as $entry)
                                        <th>
                                            {{$entry->description}}
                                        </th>
                                    @endforeach
                                    <th> note </th>
                                    <th> Delete Row</th>
                                    </thead>
                                    <tbody>
                                    @foreach($rubricRowEntries as $rowEntry)
                                        <tr>
                                            <td>
                                                {{$rowEntry->description}}
                                            </td>
                                            @foreach($rubricColumnEntries as $columnEntry)
                                                <td> <input type="radio" name={{$loop->parent->index}}  value={{$loop->index}}> </td>
                                            @endforeach
                                            <td> <textarea name={{"text".($loop->index)}} form="rubricForm"></textarea> </td>
                                            <td><button onclick="window.location='{{route('rubricEntryDelete',array('id' => $rubric->id, 'distance' => $rowEntry->distance, 'isRow' => 1))}}';"  type="button" class="btn btn-primary">Delete</button></td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>Delete Column</td>
                                        @foreach($rubricColumnEntries as $columnEntry)
                                        <td><button onclick="window.location='{{route('rubricEntryDelete',array('id' => $rubric->id, 'distance' => $columnEntry->distance, 'isRow' => 0))}}';"  type="button" class="btn btn-primary">Delete</button></td>
                                        @endforeach
                                    </tr>
                                    </tbody>
                                </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <h2 class="text-center">Add a new Rubric Entry</h2>
        <br>
        <form action = "/rubricEntryStore" method = "post" class="form-group" style="width:70%; margin-left:15%;" action="/action_page.php">

            <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>"><input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">

            <input type="hidden" class="form-control" name="rubric_id" value="{{$rubric->id}}">

            <label class="form-group"></label>
            <select class="form-control" name="is_row">
                <option value="1">Row</option>
                <option value="0">Column</option>
            </select>
            <label class="form-group"></label>
            <input type="text" class="form-control" placeholder="Description" name="description">

            <button type="submit"  value = "Add" class="btn btn-primary">Submit</button>

        </form>
    </div>
@endsection
