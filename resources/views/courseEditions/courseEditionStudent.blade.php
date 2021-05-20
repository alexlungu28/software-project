@extends('layouts.courses', ['activePage' => 'courses', 'titlePage' => __('Course Editions')])

@section('content')   <div class="content">
    <div class="container-fluid">
        <div class="row">
            @foreach($courseEditions as $edition)
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats" style="width: 120px;">
                        <div class="card-icon">
                            <a class="nav-link" href="{{ route('courseEdition', [$course_id, $edition->id]) }}">
                                <p>{{ $edition->year }}</p>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
