@extends('layouts.TA', ['activePage' => 'group', 'titlePage' => __('Week')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <button type="submit" name="update" class="btn btn-dark rounded-pill" onclick="window.location='{{ route('group', ['group_id'=>$group_id]) }}'">Back!</button>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats" style="width: 120px;">
                        <div class="card-icon">
                            <a class="nav-link" href="{{ route('attend', [$group_id, $week]) }}">
                                <p>Attendance</p>
                            </a>
                        </div>
                    </div>
                </div>
                @foreach($rubrics as $rubric)
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats" style="width: 120px;">
                            <div class="card-icon">
                                <a class="nav-link" href="{{ route('rubric', [$rubric->id, $group_id]) }}">
                                    <p>Rubric {{ $rubric->name }}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats" style="width: 120px;">
                        <div class="card-icon">
                            <a class="nav-link" href="{{ route('note', [$group_id, $week]) }}">
                                <p>Notes</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
