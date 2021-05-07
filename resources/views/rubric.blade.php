<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>Rubric Management</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>

<form action="/rubricDataStore/{{$rubric->id}}" method = "post" class="form-group" style="width:70%; margin-left:15%;" id="rubricForm">

    <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">

    <table>
        <thead>
        <tr>
            <th> {{$rubric->name}} </th>
            @foreach($rubricColumnEntries as $entry)
                <th> {{$entry->description}} </th>
            @endforeach
            <th> note </th>
        </tr>
        </thead>
        <tbody>

        @foreach($rubricRowEntries as $rowEntry)
            <tr>
                <td> {{$rowEntry->description}} </td>
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
