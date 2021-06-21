@extends('layouts.app', ['class' => 'off-canvas-sidebar', 'activePage' => 'import', 'title' => __('Import TAs/Students CSVs')])

@section('content')

<body>

<div class="container" style="display:inline-flex;">
    <div class="card bg-light mt-3" style="color: black; font-size: 1.2rem;">
        <div class="card-header">
            TA Importing
        </div>
        <div class="card-body">
            <form action="{{ route('importTA', [$edition_id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" class="form-control" required>
                <br>
                <button class="btn btn-info">Import TAs from a CSV file</button>
            </form>
        </div>
        &emsp;Below there is an example format of importing the CSV of TAs:
        <br/>  <br/>
        <div>&emsp; <img src="{{ URL::to('/assets/images/TA.png') }}"></div>

         <br/>
        &emsp;Mentions:
        <ul>
        <li>OrgDefinedId represents the student number. The "#" prefix is optional and can be removed.</li>
        <li>Username represents the netid. The "#" prefix along with the "@tudelft" suffix are optional and can be removed.</li>
        <li>Groups represent the groups a TA will be assigned to. Adding groups is optional and the column can be left blank. <br/>
            The delimiter is a semicolon followed by a space ("; ").</li>
        </ul>
        <br/>
    </div>
</div>

<div class="container" style="display:inline-flex;">
    <div class="card bg-light mt-3" style="color: black; font-size: 1.2rem;">
        <div class="card-header">
            Student Importing
        </div>
        <div class="card-body">
            <form action="{{ route('import', [$edition_id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" class="form-control" required>
                <br>
                <button class="btn btn-info" >Import Students from the CSV found at Brightspace>Grades>Export</button>
            </form>
        </div>
            &emsp;To import students, go to the Course Brightspace page and select Grades in the Course menu. Then, press on the Export button.
            <br/>
        <div>&emsp; <img src="{{ URL::to('/assets/images/GradeExport.png') }}"></div>
            <br/> <br/>
            &emsp;Tick all the boxes as in the screenshot below and press on the Export to CSV button.
            <br/> <br/>
        <img src="{{ URL::to('/assets/images/Brightspace_Export_Grade.png') }}">
    </div>
</div>

</body>
@endsection
