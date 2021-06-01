@extends('layouts.TA', ['activePage' => 'group', 'titlePage' => __('Weeks')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                @for($w=1; $w<=10; $w++)
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats" style="width: 120px;">
                            <div class="card-icon">
                                <a class="nav-link" href="{{ route('week', [$group_id, $w]) }}">
                                    <p>Week {{ $w }}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection
