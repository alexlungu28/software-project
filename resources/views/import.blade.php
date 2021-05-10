<!DOCTYPE html>
<html>
<head>
    <title>Import Export CSV</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
</head>
<body>

<div class="container">
    <div class="card bg-light mt-3">
        <div class="card-header">
            Import Export CSV
        </div>
        <div class="card-body">
            <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" class="form-control">
                <br>
                <button class="btn btn-success">Import Students from the CSV found at Brightspace>Grades>Export</button>
                <a class="btn btn-warning" href="{{ route('export') }}">Export All User Data from the Platform</a>
            </form>
        </div>
        <img src="{{ URL::to('/assets/images/Brightspace_Export_Grade.png') }}">
    </div>
</div>

</body>
</html>
