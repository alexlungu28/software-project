@extends('layouts.app', ['activePage' => 'rubrics', 'titlePage' => __('RubricView')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
              <form action="/rubricDataStore/{{$rubric->id}}" method = "post" class="form-group" style="width:70%; margin-left:15%;" id="rubricForm">

                  <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">

                  <h4 class="card-title ">{{$rubric->name}}</h4>
          </div>
          <div class="card-body">
            <form class="table-responsive">
              <table id ="table" class="table">
                <thead class=" text-primary">
                    <th></th>
                    @foreach($rubricColumnEntries as $entry)
                        <th>
                             {{$entry->description}}
                        </th>
                    @endforeach
                    <th> note </th>
                </thead>
                <tbody>
                @foreach($rubricRowEntries as $rowEntry)
                    <tr>
                        <td>
                            {{$rowEntry->description}}
                        </td>
                        @foreach($rubricColumnEntries as $columnEntry)
                            <td> <input type="radio" name={{$loop->parent->index}}  value={{$loop->index}} {{$rubricData[$loop->parent->index]->value == $loop->index ? 'checked' : ''}}> </td>
                        @endforeach
                        <td> <textarea name={{"text".($loop->index)}} form="rubricForm">{{$rubricData[$loop->index]->note}}</textarea> </td>
                    </tr>
                @endforeach
                </tbody>
              </table>
                <button type="submit"  value = "Add" class="btn btn-primary">Submit</button>
            </form>
            </div>
          </div>

        </div>
        </div>
      </div>
      </div>


@endsection
