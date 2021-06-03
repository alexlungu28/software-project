@extends('layouts.courses', ['activePage' => 'courses', 'titlePage' => __('Course Editions')])

@section('content')   <div class="content">
    <div class="container-fluid">
        <button type="submit" name="update" class="btn btn-dark rounded-pill" onclick="window.location='{{ route('courses') }}'">Back!</button>
        @foreach($courseEditions as $edition)
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats" style="width: 120px;">
                    <div class="card-icon">
                        <a class="nav-link" href="{{ route('groups', [$edition->id]) }}">
                            <p><span style="color: #00A6D6; ">{{ $edition->year }}</span></p>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </div>
</div>
@endsection
