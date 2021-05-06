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
        @for($i = $width; $i < $width + $length; $i++)
                <td> {{$rubricRowEntries[$i]->description}} </td>
                @for($j = 0; $j < $width; $j++)
                    <td> <input type="radio" name={{$i - $width}} {{$rubricData[$i - $width]->value == $j ? 'checked' : ''}} value={{$j}}> </td>
                @endfor
                <td> <textarea name={{"text".$i - $width}} form="rubricForm">{{$rubricData[$i - $width]->note}}</textarea> </td>
            </tr>
        @endfor
        </tbody>
    </table>
    <button type="submit"  value = "Add" class="btn btn-primary">Submit</button>
</form>
