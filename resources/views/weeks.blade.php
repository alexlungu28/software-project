@extends('layouts.app', ['activePage' => 'group', 'titlePage' => __('Weeks')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <button type="submit" name="update" class="btn btn-dark rounded-pill" onclick="window.location='{{ route('groups', ['edition_id'=>$edition_id]) }}'">Back!</button>
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
                                        <th>Week</th>
                                        <th>Regarding</th>
                                        <th>Content</th>
                                        <th>Problem Signal</th>
                                        </thead>
                                        <tbody>
                                        @foreach($group->groupnotes->sortBy('week') as $groupnote)
                                            @if($groupnote->problem_signal >= 2)
                                            <tr>
                                                <td>

                                                </td>
                                                <td>
                                                    {{$groupnote->week}}
                                                </td>
                                                <td>
                                                    Group
                                                </td>
                                                <td>
                                                    <textarea readonly=true style="width: 100%">{{$groupnote->note}}</textarea>
                                                </td>
                                                <td>
                                                    {{$groupnote->problem_signal}}
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                        @foreach($group->notes->sortBy('week') as $note)
                                            @if($note->problem_signal >= 2)
                                                <tr>
                                                    <td>

                                                    </td>
                                                    <td>
                                                        {{$note->week}}
                                                    </td>
                                                    <td>
                                                        {{$note->user->first_name . ' ' . $note->user->last_name}}
                                                    </td>
                                                    <td>
                                                        <textarea readonly=true style="width: 100%">{{$note->note}}</textarea>
                                                    </td>
                                                    <td>
                                                        {{$note->problem_signal}}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
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
