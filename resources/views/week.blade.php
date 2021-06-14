@extends('layouts.app', ['activePage' => 'group', 'titlePage' => __('Week')])

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

        <div class="container" style="display:inline-flex;">
            <div class="card bg-light mt-3" style="color: black; font-size: 1.2rem;">
                <div class="card-header">
                    Git analysis importing
                </div>
                <div class="card-body">
                    <form action="{{ route('importGitanalysis', [$group_id, $week]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" class="form-control">
                        <br>
                        <button class="btn btn-info">Import Git analysis from a txt file containing JSON</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
