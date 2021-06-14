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
                                                <p>
                                                <a class="nav-link" href="{{ route('group', $notification->data['Deadline passed']['group_id']) }}">
                                                    {{$users[$key]->first_name
                                                    . ' ' . $users[$key]->last_name
                                                    . '; Group ' . $notification->data['Deadline passed']['group_id']
                                                    . '; ' . $notification->data['Deadline passed']['end_day']}}</a>
                                                <form method="post" action="/notifications/markAsRead">
                                                    {{ method_field('PUT') }}
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="id" value="{{ $notification->id }}">
                                                    <input type="hidden" name="edition_id" value="{{$edition_id}}">
                                                    <button class="btn btn-primary" type="submit">Mark as read</button>
                                                </form>
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
