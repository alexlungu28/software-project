@extends('layouts.courses', ['activePage' => 'courses', 'titlePage' => __('Courses')])

@section('content')   <div class="content">
        <div class="container-fluid">
            <div class="row">
                @foreach($courses as $course)
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats" style="width: 120px;">
                            <div class="card-icon">
                                <a class="nav-link" href="{{ route('course', $course->id) }}">
                                    <p>{{ $course->course_number }}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
