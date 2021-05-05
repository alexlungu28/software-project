<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<table>
    <thead>
    <tr>
        <th> {{$rubric->name}} </th>
        @foreach($rubric->rubricEntry as $entry)
            @if(!$entry->is_row)
                <th> {{$entry->description}} </th>
            @endif
        @endforeach
        <th> note </th>
    </tr>
    </thead>
    <tbody>
    @for($i = 0; $i < $rubric->length; $i++)
        <tr>
            <td> {{$rubricEntries[$rubric->width + $i]->description}} </td>
            @for($j = 0; $j < $rubric->width; $j++)
                <td> <input type="radio" id={{$i . $j}} name={{$i}} {{$rubricData[$i]->value == $j ? 'checked' : ''}}> </td>
            @endfor
            <td> <textarea id={{"text".$i}}>{{$rubricData[$i]->note}}</textarea> </td>
        </tr>
    @endfor
    </tbody>
</table>
