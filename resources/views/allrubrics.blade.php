@extends('layouts.app', ['activePage' => 'rubrics', 'titlePage' => __('Rubrics')])

@section('content')
    <div class="content" style="display: flex;">
        <div class="container-fluid">
            <div class="row">
                @foreach($rubrics as $rubric)
                    <div class="card card-stats" style="width: 120px; margin-left:10px; margin-right:10px; float:left">
                        <div class="card-icon">
                            <a class="nav-link" href="{{ route('teacherRubric', [$rubric->id, $edition_id]) }}">
                                <p>{{ $rubric->name }}</p>
                                <p> Week: {{ $rubric->week }}</p>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="container-fluid">
            <div class="container" style="width: 50%">
                <button onclick="toggle('rubricCreate')" class="btn btn-primary toggle" style="background-color: #00A6D6">
                    <h2 class="text-center" style="font-size: 1.2em;">Add rubric</h2>
                </button>
                <br>
                <form id="rubricCreate" action = "/rubricStore" method = "post" class="form-group" style="width:70%; margin-left:15%; display: none" action="/action_page.php">

                    <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>"><input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">

                    <input type = "hidden" name = "edition" value = {{$edition_id}}>

                    <br>
                    <input type="text" class ="form-control" placeholder="Name" name="name" required>
                    <br/>
                    <input type="number" class ="form-control" min="0" max="15" placeholder="1" name="week">
                    <br/>

                    <button type="submit"  value = "Add" class="btn btn-primary" style="background-color: #00A6D6;">Submit</button>
                </form>
            </div>
            <div class="container" style="width: 50%">
                <button onclick="toggle('rubricUpdate')" class="btn btn-primary toggle" style="background-color: #00A6D6;">
                    <h2 class="text-center" style="font-size: 1.2em;">Update rubric</h2>
                </button>
                <br>
                <form id="rubricUpdate" action = "/rubricUpdate" method = "post" class="form-group" style="width:70%; margin-left:15%; display: none" action="/action_page.php">

                    <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
                    @method('PUT')
                    <select class="form-control" name="id" required>
                        @foreach($rubrics as $rubric)
                            <option value="{{$rubric->id}}">{{$rubric->name}}</option>
                        @endforeach
                    </select>

                    <br>
                    <input type="text" class="form-control" placeholder="New name" name="name" required>
                    <br>
                    <input type="number" class ="form-control" min="0" max="15" placeholder="1" name="week">
                    <br>
                    <button type="submit"  value = "Add" class="btn btn-primary" style="background-color: #00A6D6;">Submit</button>

                </form>
            </div>
            <div class="container" style="width: 50%">
                <button onclick="toggle('rubricDelete')" class="btn btn-primary toggle"
                        style="background-color: #00A6D6;">
                    <h2 class="text-center" style="font-size: 1.2em;">Delete rubric</h2>
                </button>
                <br>
                <form id="rubricDelete" action = "/rubricDestroy" method = "post" class="form-group" style="width:70%; margin-left:15%; display: none">

                    <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
                    @method('DELETE')
                    <select class="form-control" name="id" required>
                        @foreach($rubrics as $rubric)
                            <option value="{{$rubric->id}}">{{$rubric->name}}</option>
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
                <button onclick="toggle('rubricRestore')" class="btn btn-primary toggle"
                        style="background-color: #00A6D6;">
                    <h2 class="text-center" style="font-size: 1.2em">Restore deleted Rubric</h2>
                </button>
                <br>
                <form id="rubricRestore" action = "{{ route('rubricRestore') }}" method = "post" class="form-group" style="width:70%; margin-left:15%;" action="action_page.php">
                    <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
                    @method("PUT")
                    <select class="form-control" name="id" required>
                        @foreach($deletedRubrics as $deletedRubric)
                            <option value="{{$deletedRubric->id}}">{{$deletedRubric->name}}</option>
                        @endforeach
                    </select>
                    <button type="submit"  value = "Add" class="btn btn-primary">Submit</button>
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
