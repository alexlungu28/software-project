@extends('layouts.app', ['activePage' => 'notifications', 'titlePage' => __('Notifications')])

@section('content')
    <div class="content">
        <div class="container-fluid">
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
                                            @foreach($notifications as $key=>$notification)
                                                <a class="nav-link" href="{{ route('group', $notification->data['Deadline passed']['group_id']) }}">
                                                    <p>{{$users[$key]->first_name
                                                    . ' ' . $users[$key]->last_name
                                                    . '; Group ' . $notification->data['Deadline passed']['group_id']
                                                    . '; ' . $notification->data['Deadline passed']['end_day']}}</p>
                                                </a>
                                            @endforeach
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
