@extends('layouts.app', ['activePage' => 'groups', 'titlePage' => __('Dashboard')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                @foreach($groups as $group)
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats" style="width: 120px;">
                            <div class="card-icon">
                                <a class="nav-link" href="{{ route('group', $group->id) }}">
                                    <p>{{ $group->group_name }}</p>
                                    <p>@php
                                            $i = 0;
                                            foreach($group->notes as $note)
                                                if($note->problem_signal != 0)
                                                    $i++;
                                            if ($i == 1)
                                                echo $i . ' group problem';
                                            if ($i > 1)
                                                echo $i . ' group problems';
                                        @endphp
                                    </p>

                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
