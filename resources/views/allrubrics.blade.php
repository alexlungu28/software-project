@extends('layouts.app', ['activePage' => 'rubrics', 'titlePage' => __('Dashboard')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                @foreach($rubrics as $rubric)
                    <div class="card card-stats" style="width: 120px; margin-left:10px; margin-right:10px; float:left">
                        <div class="card-icon">
                            <a class="nav-link" href="{{ route('teacherRubric', [$rubric->id, $edition_id]) }}">
                                <p>{{ $rubric->name }}</p>
                                <p> Week: {{ $rubric->week }}</p>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats" style="width: 120px;">
                        <div class="card-icon">
                            <a class="nav-link" href="{{ route('rubricCreate', $edition_id) }}">
                                <p>Create rubric</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats" style="width: 120px;">
                        <div class="card-icon">
                            <a class="nav-link" href="{{ route('rubricEdit') }}">
                                <p>Update rubric</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats" style="width: 120px;">
                        <div class="card-icon">
                            <a class="nav-link" href="{{ route('rubricDelete') }}">
                                <p>Delete rubric</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
@endsection
