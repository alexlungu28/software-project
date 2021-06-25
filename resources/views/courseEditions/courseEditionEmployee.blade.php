@extends('layouts.courses', ['activePage' => 'course_editions', 'titlePage' => __('Courses')])

@section('content')
    <div class="content" style="display: flex">
        <div class="container-fluid">
            <button type="submit" name="update" class="btn btn-dark rounded-pill" onclick="window.location='{{ route('courses') }}'">Back!</button>
            @foreach($courseEditions as $edition)
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats" style="width: 120px;">
                        <div class="card-icon">
                            <a class="nav-link" href="{{ route('groups', [$edition->id]) }}">
                                <p>{{ $edition->year }}</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="container-fluid">
            <div class="container" style="width: 50%">
                <button onclick="toggle('courseEditionCreate')" class="btn btn-primary toggle" style="background-color: #00A6D6">
                    <h2 class="text-center" style="font-size: 1.2em;">Add course edition</h2>
                </button>
                <br>
                <form id='courseEditionCreate' action = "/courseEditionStore/{{$course_id}}" method = "post" class="form-group" style="width:70%; margin-left:15%; display: none" action="/action_page.php">

                    <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>"><input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">

                    <input type="text" class ="form-control" placeholder="Year" name="year">
                    <br/>

                    <button type="submit"  value = "Add" class="btn btn-primary" style="background-color: #00A6D6;">Submit</button>
                </form>
            </div>
            <div class="container" style="width: 50%">
                <button onclick="toggle('courseEditionUpdate')" class="btn btn-primary toggle" style="background-color: #00A6D6">
                    <h2 class="text-center" style="font-size: 1.2em;">Update course edition</h2>
                </button>
                <br>
                <form id='courseEditionUpdate' action = "/courseEditionUpdate/{{$course_id}}" method = "post" class="form-group" style="width:70%; margin-left:15%; display:none" action="/action_page.php">

                    <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
                    @method('PUT')
                    <select class="form-control" name="id">
                        @foreach($courseEditions as $edition)
                            <option value="{{$edition->id}}">{{$edition->year}}</option>
                        @endforeach
                    </select>

                    <br/>
                    <input type="text" class="form-control" placeholder="New year" name="year">
                    <br/>

                    <button type="submit"  value = "Add" class="btn btn-primary" style="background-color: #00A6D6;">Submit</button>

                </form>
            </div>
            <div class="container" style="width: 50%">
                <button onclick="toggle('courseEditionDelete')" class="btn btn-primary toggle" style="background-color: #00A6D6">
                    <h2 class="text-center" style="font-size: 1.2em;">Delete course edition</h2>
                </button>
                <br>
                <form id='courseEditionDelete' action = "/courseEditionDestroy" method = "post" class="form-group" style="width:70%; margin-left:15%; display:none">

                    <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
                    @csrf
                    @method('DELETE')
                    {{ method_field('DELETE') }}
                    <select class="form-control" name="id">
                        @foreach($courseEditions as $edition)
                            <option value="{{$edition->id}}">{{$edition->year}}</option>
                        @endforeach
                    </select>
                    <br/>
                    <input type="checkbox" name="hardDelete" value="Yes" /> Permanently Delete
                    <br/>
                    <br/>

                    <button type="submit"  value = "Add" class="btn btn-primary" style="background-color: #00A6D6;">Submit</button>

                </form>
            </div>
            <div class="container" style="width: 50%">
                <button onclick="toggle('courseEditionRestore')" class="btn btn-primary toggle"
                        style="background-color: #00A6D6;">
                    <h2 class="text-center" style="font-size: 1.2em;">Restore course edition</h2>
                </button>
                <br>
                <form id="courseEditionRestore" action = "/courseEditionRestore" method = "post" class="form-group" style="width:70%; margin-left:15%; display: none">

                    <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
                    @method('PUT')
                    <select class="form-control" name="id">
                        @foreach($deletedEditions as $edition)
                            <option value="{{$edition->id}}">{{$edition->year}}</option>
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
