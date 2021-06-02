@extends('layouts.courses', ['activePage' => 'courses', 'titlePage' => __('Courses')])

@section('content')
    <div class="content" style="display: flex;">
        <div class="container-fluid">
            @foreach($courses as $course)
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats" style="width: 120px;">
                        <div class="card-icon">
                            <a class="nav-link" href="{{ route('course', $course->id) }}">
                                <p><span style="color: #00A6D6; ">{{ $course->course_number }}</span></p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="container-fluid">
            <div class="container" style="width: 50%">
                <button onclick="toggle('courseCreate')" class="btn btn-primary toggle" style="background-color: #00A6D6">
                    <h2 class="text-center" style="font-size: 1.2em;">Add course</h2>
                </button>
                <br>
                <form id="courseCreate" action = "/courseStore" method = "post" class="form-group" style="width:70%; margin-left:15%; display: none" action="/action_page.php">

                    <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>"><input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">

                    <input type="text" class ="form-control" placeholder="Course number" name="course_number">
                    <br/>
                    <input type="text" class ="form-control" placeholder="Description" name="description">
                    <br/>

                    <button type="submit"  value = "Add" class="btn btn-primary" style="background-color: #00A6D6;">Submit</button>
                </form>
            </div>
            <div class="container" style="width: 50%">
                <button onclick="toggle('courseUpdate')" class="btn btn-primary toggle" style="background-color: #00A6D6;">
                    <h2 class="text-center" style="font-size: 1.2em;">Update course</h2>
                </button>
                <br>
                <form id="courseUpdate" action = "/courseUpdate" method = "post" class="form-group" style="width:70%; margin-left:15%; display: none" action="/action_page.php">

                    <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
                    @method('PUT')
                    <select class="form-control" name="id">
                        @foreach($courses as $course)
                            <option value="{{$course->id}}">{{$course->course_number}}</option>
                        @endforeach
                    </select>

                    <br/>
                    <input type="text" class="form-control" placeholder="New course number" name="course_number">
                    <br/>
                    <input type="text" class="form-control" placeholder="New description" name="description">
                    <br/>

                    <button type="submit"  value = "Add" class="btn btn-primary" style="background-color: #00A6D6;">Submit</button>

                </form>
            </div>
            <div class="container" style="width: 50%">
                <button onclick="toggle('courseDelete')" class="btn btn-primary toggle"
                        style="background-color: #00A6D6;">
                    <h2 class="text-center" style="font-size: 1.2em;">Delete course</h2>
                </button>
                <br>
                <form id="courseDelete" action = "/courseDestroy" method = "post" class="form-group" style="width:70%; margin-left:15%; display: none">

                    <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
                    @csrf
                    @method('DELETE')
                    {{ method_field('DELETE') }}
                    <select class="form-control" name="id">
                        @foreach($courses as $course)
                            <option value="{{$course->id}}">{{$course->course_number}}</option>
                        @endforeach
                    </select>
                    <br/>

                    <button type="submit"  value = "Add" class="btn btn-primary" style="background-color: #00A6D6;">Submit</button>

                </form>
            </div>
        </div>
    </div>
@endsection

<script>
    function toggle(id) {
        const x = document.getElementById(id);
        const forms = document.getElementsByClassName('form-group');
        if (x.style.display === "none") {
            Array.from(forms).forEach((el) => {
                el.style.display = "none";
            });
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
</script>
