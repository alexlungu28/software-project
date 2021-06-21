@extends('layouts.app', ['activePage' => 'group', 'titlePage' => __('Weeks')])


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

@section('content')

    @php
    $user =auth()->user();
    $userId = $user->id;
    $role = \App\Models\CourseEditionUser::where('user_id', '=', $userId)->where('course_edition_id', '=', $edition_id)->first()->role;
    @endphp

    @if ($role == "lecturer" || $role == "HeadTA")
       @include ('weeks_staff')
    @endif

    @if ($role == "TA")
        @include ('weeks_TA')
    @endif

@endsection
