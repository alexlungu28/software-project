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
                                            @if(count($notifications) != 0)
                                                <form method="post" action="/notifications/markAllAsRead">
                                                    {{ method_field('PUT') }}
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="edition_id" value="{{$edition_id}}">
                                                    <button class="btn btn-primary" type="submit">Mark all as read</button>
                                                    <br/>
                                                </form>
                                            @else
                                                <h3>You have no new notifications.</h3>
                                            @endif
                                            @foreach($notifications as $key=>$notification)
                                                <tr>
                                                    <div class="card card-stats">
                                                        @php
                                                            $groupId = $notification->data['Deadline passed']['group_id'];
                                                            $group = \App\Models\Group::find($groupId);
                                                            $edition = \App\Models\CourseEdition::find($group->course_edition_id);
                                                            $sameEdition = $edition->id == $edition_id;
                                                            $course = \App\Models\Course::find($edition->course_id);
                                                        @endphp
                                                        <button class="btn">
                                                            <a class="nav-link" href="{{ route('group', $groupId) }}"
                                                            style="color: black;">
                                                                @if($sameEdition)
                                                                    {{$users[$key]->first_name
                                                                    . ' ' . $users[$key]->last_name
                                                                    . ' - ' . $group->group_name
                                                                    . ' - ' . $notification->data['Deadline passed']['end_day']}}
                                                                @else
                                                                    {{$users[$key]->first_name
                                                                    . ' ' . $users[$key]->last_name
                                                                    . ' - ' . $course->description
                                                                    . ' - ' . $edition->year
                                                                    . ' - ' . $group->group_name
                                                                    . ' - ' . $notification->data['Deadline passed']['end_day']}}
                                                                @endif
                                                            </a>
                                                        </button>
                                                        <form method="post" action="/notifications/markAsRead">
                                                            {{ method_field('PUT') }}
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="id" value="{{ $notification->id }}">
                                                            <input type="hidden" name="edition_id" value="{{$edition_id}}">
                                                            <button class="btn btn-primary" type="submit">Mark as read</button>
                                                        </form>
                                                    </div>
                                                </tr>
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
