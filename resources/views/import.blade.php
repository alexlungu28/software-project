@extends('layouts.app', ['class' => 'off-canvas-sidebar', 'activePage' => 'import', 'title' => __('Import/Export')])

@section('content')

<body>

<div class="container">
    <div class="card bg-light mt-3">
        <div class="card-header">
            <span style="color: black; "> TA Importing </span>
        </div>
        <div class="card-body">
            <form action="{{ route('importTA', [$edition_id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" class="form-control">
                <br>
                <button class="btn btn-success">Import TAs from a CSV file</button>
            </form>
        </div>
        <img src="{{ URL::to('/assets/images/TA.png') }}">
    </div>
</div>

<div class="container">
    <div class="card bg-light mt-3">
        <div class="card-header">
            <span style="color: black; "> Student Importing </span>
        </div>
        <div class="card-body">
            <form action="{{ route('import', [$edition_id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" class="form-control">
                <br>
                <button class="btn btn-success" >Import Students from the CSV found at Brightspace>Grades>Export</button>
                <a class="btn btn-danger" href="{{ route('export', [$edition_id]) }}">Export All User Data from the Platform</a>
            </form>
        </div>
        <img src="{{ URL::to('/assets/images/Brightspace_Export_Grade.png') }}">
    </div>
</div>

</body>
@endsection
