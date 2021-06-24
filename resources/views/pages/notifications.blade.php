@extends('layouts.app', ['activePage' => 'notifications', 'titlePage' => __('Notifications')])

@section('content')
    <div class="content">
        @php
            $individualNotifications = 0;
            $groupNotifications = 0;
            foreach($notifications as $notification) {
                if (isset($notification->data['Deadline passed'])) {
                    $individualNotifications++;
                } elseif (isset($notification->data['Deadline passed group'])) {
                    $groupNotifications++;
                }
            }
        @endphp
        <div class="row d-flex">
        <div class="container-fluid col-6 float-left">
            <div class="row" style="margin-right: 5%">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Deadline passed - individual interventions</h4>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="profile">
                                <table class="table">
                                    <tbody>
                                    @if($individualNotifications != 0)
                                        <form method="post" action="{{ route('markAllAsRead') }}">
                                            {{ method_field('PUT') }}
                                            {{ csrf_field() }}
                                            <input type="hidden" name="edition_id" value="{{$edition_id}}">
                                            <input type="hidden" name="individual" value="1">
                                            <button class="btn btn-primary" type="submit">Mark all as read</button>
                                            <br/>
                                        </form>
                                    @else
                                        <h3>You have no new notifications.</h3>
                                    @endif
                                    @foreach($notifications as $key=>$notification)
                                        @if(isset($notification->data['Deadline passed']))
                                        <tr>
                                            <div class="card card-stats">
                                                @php
                                                    $groupId = $notification->data['Deadline passed']['group_id'];
                                                    $group = \App\Models\Group::find($groupId);
                                                    $edition = \App\Models\CourseEdition::find($group->course_edition_id);
                                                    $sameEdition = $edition->id == $edition_id;
                                                    $course = \App\Models\Course::find($edition->course_id);
                                                    $user_id = $notification->data['Deadline passed']['user_id'];
                                                    $user = \App\Models\User::find($user_id);
                                                @endphp
                                                <button class="btn">
                                                    <a class="nav-link" href="{{ route('group', $groupId) }}"
                                                    style="color: black;">
                                                        @if($sameEdition)
                                                            {{$user->first_name
                                                            . ' ' . $user->last_name
                                                            . ' - ' . $group->group_name
                                                            . ' - ' . $notification->data['Deadline passed']['end_day']}}
                                                        @else
                                                            {{$user->first_name
                                                            . ' ' . $user->last_name
                                                            . ' - ' . $course->description
                                                            . ' - ' . $edition->year
                                                            . ' - ' . $group->group_name
                                                            . ' - ' . $notification->data['Deadline passed']['end_day']}}
                                                        @endif
                                                    </a>
                                                </button>
                                                <form method="post" action="{{ route('markAsRead') }}">
                                                    {{ method_field('PUT') }}
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="id" value="{{ $notification->id }}">
                                                    <input type="hidden" name="edition_id" value="{{$edition_id}}">
                                                    <button class="btn btn-primary" type="submit">Mark as read</button>
                                                </form>
                                            </div>
                                        </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid col-6 float-right">
            <div class="row" style="margin-left: 5%">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Deadline passed - group interventions</h4>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="profile">
                                <table class="table">
                                    <tbody>
                                    @if($groupNotifications != 0)
                                        <form method="post" action="{{ route('markAllAsRead') }}">
                                            {{ method_field('PUT') }}
                                            {{ csrf_field() }}
                                            <input type="hidden" name="edition_id" value="{{$edition_id}}">
                                            <input type="hidden" name="individual" value="0">
                                            <button class="btn btn-primary" type="submit">Mark all as read</button>
                                            <br/>
                                        </form>
                                    @else
                                        <h3>You have no new notifications.</h3>
                                    @endif
                                    @foreach($notifications as $key=>$notification)
                                        @if(isset($notification->data['Deadline passed group']))
                                            <tr>
                                                <div class="card card-stats">
                                                    @php
                                                        $groupId = $notification->data['Deadline passed group']['group_id'];
                                                        $group = \App\Models\Group::find($groupId);
                                                        $edition = \App\Models\CourseEdition::find($group->course_edition_id);
                                                        $sameEdition = $edition->id == $edition_id;
                                                        $course = \App\Models\Course::find($edition->course_id);
                                                    @endphp
                                                    <button class="btn">
                                                        <a class="nav-link" href="{{ route('group', $groupId) }}"
                                                           style="color: black;">
                                                            @if($sameEdition)
                                                                {{$group->group_name
                                                                . ' - ' . $notification->data['Deadline passed group']['end_day']}}
                                                            @else
                                                                {{$course->description
                                                                . ' - ' . $edition->year
                                                                . ' - ' . $group->group_name
                                                                . ' - ' . $notification->data['Deadline passed group']['end_day']}}
                                                            @endif
                                                        </a>
                                                    </button>
                                                    <form method="post" action="{{ route('markAsRead') }}">
                                                        {{ method_field('PUT') }}
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="id" value="{{ $notification->id }}">
                                                        <input type="hidden" name="edition_id" value="{{$edition_id}}">
                                                        <button class="btn btn-primary" type="submit">Mark as read</button>
                                                    </form>
                                                </div>
                                            </tr>
                                        @endif
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
