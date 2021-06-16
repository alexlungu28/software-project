@extends('layouts.app', ['activePage' => 'interventions', 'titlePage' => __('Interventions')])
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">

</head>
@section('content')

    <div class="content">
        <div class="container-fluid">

            @include ('/interventions/intervention_create_modal')

            <ul class="nav nav-pills " id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-allInterventions-tab" data-toggle="pill" href="#allInterventions" role="tab" aria-controls="allInterventions" aria-selected="true">Interventions - Individual</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-problemCases-tab" data-toggle="pill" href="#problemCases" role="tab" aria-controls="problemCases" aria-selected="false">Problem Cases - Individual</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="" role="tab" aria-controls="problemCases" aria-selected="false">Interventions - Group</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="pills-groupProblemCases-tab" data-toggle="pill" href="#groupProblemCases" role="tab" aria-controls="pills-groupProblemCases" aria-selected="false">Problem Cases - Group</a>
                </li>

            </ul>
            <div class="tab-content" id="pills-tabContent">

                @include ('/interventions/interventions_all_tab')

                @include ('/interventions/interventions_problem_cases_tab')

               @include ('/group_interventions/group_problem_cases_tab')
                </div>

            </div>

</div>

@endsection
