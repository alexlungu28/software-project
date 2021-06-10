@extends('layouts.app', ['activePage' => 'notifications', 'titlePage' => __('Notifications')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            @foreach($notifications as $notification)
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats" style="width: 120px;">
                            <div class="card-icon">
                                <a class="nav-link" href="{{ route('group', $notification->group_id) }}">
                                    <p>{{ $course->course_number }}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title">Deadline passed</h4>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="profile">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                @for($i = 0; $i < count($interventions); $i++)
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <div class="card card-stats" style="width: 120px;">
                                                            <div class="card-icon">
                                                                <a class="nav-link" href="{{ route('group', $interventions[$i]->group_id) }}">
                                                                    <p>{{ $users[$i]->first_name . ' ' . $users[$i]->last_name }}</p>
                                                                    <p>{{ 'Group ' . $interventions[$i]->group_id }}</p>
                                                                    <p>{{ $interventions[$i]->end_day }}</p>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endfor
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection
