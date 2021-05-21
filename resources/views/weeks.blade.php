@extends('layouts.app', ['activePage' => 'groups', 'titlePage' => __('Weeks')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                @for($i=1; $i<=10; $i++)
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats" style="width: 120px;">
                            <div class="card-icon">
                                <a class="nav-link" href="{{ route('week', [$group_id, $i]) }}">
                                    <p>Week {{ $i }}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection
