@extends('layouts.app', ['activePage' => 'notificationSettings', 'titlePage' => __('Notification Settings')])

@section('content')
    <div class="content">
        <div class="row d-flex">
            <div class="container-fluid col-8">
                <div class="row" style="margin-right: 5%">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Notification Settings</h4>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="profile">
                                    <table class="table">
                                        <tbody>
                                        <form action="{{ route('updateNotificationSettings') }}" method="post" class="form-group">
                                            <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
                                            @method('PUT')
                                            <input type="hidden" name="edition_id" value="{{$edition_id}}">
                                            @php
                                                $user = auth()->user();
                                                $notificationSettings = DB::table('notification_settings')
                                                ->where('user_id', '=', $user->id)->get()->first();
                                                if($notificationSettings != null) {
                                                    $individual = $notificationSettings->user_deadlines;
                                                    $group = $notificationSettings->group_deadlines;
                                                } else {
                                                    $individual = 0;
                                                    $group = 0;
                                                }
                                            @endphp
                                            <tr>
                                                <td>
                                                    <p>Deadline passed for individual intervention</p>
                                                </td>
                                                <td>
                                                    <select class="form-control" name="individual">
                                                        <option value="1" @if($individual == 1) selected @endif>Notify me in Gradinator and on email</option>
                                                        <option value="2" @if($individual == 2) selected @endif>Notify me only in Gradinator</option>
                                                        <option value="3" @if($individual == 3) selected @endif>Notify me only on email</option>
                                                        <option value="4" @if($individual == 4) selected @endif>Do not notify me</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p>Deadline passed for group intervention</p>
                                                </td>
                                                <td>
                                                    <select class="form-control" name="group">
                                                        <option value="1" @if($group == 1) selected @endif>Notify me in Gradinator and on email</option>
                                                        <option value="2" @if($group == 2) selected @endif>Notify me only in Gradinator</option>
                                                        <option value="3" @if($group == 3) selected @endif>Notify me only on email</option>
                                                        <option value="4" @if($group == 4) selected @endif>Do not notify me</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </tr>
                                        </form>
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
