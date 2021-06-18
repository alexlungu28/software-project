@extends('layouts.app', ['activePage' => 'groups', 'titlePage' => __('Dashboard')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <button type="submit" name="update" class="btn btn-dark rounded-pill" onclick="window.location='{{ route('course', ['id'=>$course_id]) }}'">Back!</button>
            <div class="row">
                @foreach($groups as $group)
                    <div class="card card-stats" style="width: 170px; margin-left:10px; margin-right:10px; float:left">
                        <div class="card-icon">
                            <a class="nav-link" href="{{ route('group', $group->id) }}">
                                <p>{{ $group->group_name }}</p>
                                @php
                                    $i = 0;
                                    foreach($group->groupnotes as $note)
                                        if($note->problem_signal != 0)
                                            $i++;
                                    if ($i == 1)
                                        echo $i . ' group problem <br>';
                                    if ($i > 1)
                                        echo $i . ' group problems <br>';
                                @endphp
                                @php
                                    $i = 0;
                                    foreach($group->notes as $note)
                                        if($note->problem_signal != 0)
                                            $i++;
                                    if ($i == 1)
                                        echo $i . ' individual problem';
                                    if ($i > 1)
                                        echo $i . ' individual problems';
                                @endphp
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
