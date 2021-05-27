@extends('layouts.app', ['activePage' => 'week', 'titlePage' => __('Week')])

@section('content')
    <div class="content">
        <div class="container-fluid">
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
