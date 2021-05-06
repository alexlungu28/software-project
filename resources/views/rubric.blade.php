<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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
        <tr>
            <td> {{$rubricRowEntries[$i]->description}} </td>
            @for($j = 0; $j < $width; $j++)
                <td> <input type="radio" id={{$i . $j}} name={{$i}} {{$rubricData[$i - $width]->value == $j ? 'checked' : ''}}> </td> <!-- Fill in data based on values from rubricData -->
            @endfor
            <td> <textarea id={{"text".$i}}>{{$rubricData[$i - $width]->note}}</textarea> </td>
        </tr>
    @endfor
    </tbody>
</table>
