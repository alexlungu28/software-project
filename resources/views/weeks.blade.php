@extends('layouts.app', ['activePage' => 'group', 'titlePage' => __('Weeks')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                @for($w=1; $w<=10; $w++)
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats" style="width: 120px;">
                            <div class="card-icon">
                                <a class="nav-link" href="{{ route('week', [$group_id, $w]) }}">
                                    <p>Week {{ $w }}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Problem cases</h4>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="profile">
                                    <table class="table">
                                        <thead class="text-primary">
                                        <th>Solved</th>
                                        <th>Content</th>
                                        <th>Problem Signal</th>
                                        </thead>
                                        <tbody>
                              {{--          @foreach($group->notes as $note)
                                            @if($note->problem_signal >= 1)
                                            <tr>
                                                <td>

                                                </td>
                                                <td>
                                                    <textarea readonly=true style="width: 100%">{{$note->content}}</textarea>
                                                </td>
                                                <td>
                                                    {{$note->problem_signal}}
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach--}}
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
