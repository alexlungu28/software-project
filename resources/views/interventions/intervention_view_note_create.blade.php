<div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show" id={{"noteCreateInt" . $note->id}} role="tabpanel" aria-labelledby="pills-note-tab">
        <h4 ><b>Problematic Note</b></h4>
        <div class="form-group">
            <label for="name">Week</label>
            <h4 ><b>{{$note->week}}</b></h4>
        </div>

        <div class="form-group">
            @if($note->problem_signal == 1)
                <button class="btn btn-success rounded-pill" cursor="default" >All good!</button>
            @elseif($note->problem_signal == 2)
                <button class="btn btn-warning rounded-pill" cursor="default" >Warning!</button>
            @elseif($note->problem_signal == 3)
                <button class="btn btn-danger rounded-pill" cursor="default" >Problematic!</button>
            @else
                {{$problemSignal = " "}}
            @endif
        </div>


        <div class="form-group">
            <label for="viewReason">Note</label>
            <h4>
                <div style="overflow-x: hidden; overflow-y:auto;
                                                                   text-overflow: clip;
                                                                   display: -webkit-box;
                                                                   -webkit-line-clamp: 5; /* number of lines to show */
                                                                   -webkit-box-orient: vertical;">
                    {{$note->note}}
                </div>
            </h4>
        </div>



        <button type="button" class="btn btn-outline-success btn-block" id="pills-hide-tab" data-toggle="pill" href={{"#hideCreate" . $note->id}} role="tab" aria-controls="active" aria-selected="false">Hide Note</button>

    </div>

    <div class="tab-pane fade show active" id={{"hideCreate" . $note->id}} role="tabpanel" aria-labelledby="pills-hide-tab">
        <div>
            <button type="button" class="btn btn-default" id="pills-note-tab" data-toggle="pill" href={{"#noteCreateInt" . $note->id}} role="tab" aria-controls="active" aria-selected="false">Note</button>
        </div>
    </div>
</div>
