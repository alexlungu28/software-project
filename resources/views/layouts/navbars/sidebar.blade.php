<div class="sidebar" data-color="azure" data-background-color="white" data-image="{{ asset('material') }}/img/sidebar-1.jpg">
  <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
  <div class="logo">
    <a class="simple-text logo-normal">
      {{ __('Gradinator') }}
    </a>
  </div>
  <div class="sidebar-wrapper">
    <ul class="nav">
        <li class="nav-item{{ $activePage == 'courses' ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('courses') }}">
                <i class="material-icons">dashboard</i>
                <p>{{ __('Courses') }}</p>
            </a>
        </li>
      <li class="nav-item{{ $activePage == 'notifications' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('notifications', [$edition_id]) }}">
          <i class="material-icons">notifications</i>
          <p>{{ __('Notifications') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'rubrics' ? ' active' : '' }}">
        <a class="nav-link" href={{ route('viewRubrics', [$edition_id]) }}>
          <i class="material-icons">rubrics</i>
          <p>{{ __('Rubrics') }}</p>
        </a>
      </li>
        <li class="nav-item{{ $activePage == 'groups' ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('groups', [$edition_id]) }}">
                <i class="material-icons">groups</i>
                <p>{{ __('Groups') }}</p>
            </a>
        </li>

        <li class="nav-item{{ $activePage == 'interventions' ? ' active' : '' }}">
            <a class="nav-link" href={{ route('interventions', [$edition_id]) }}>
                <i class="material-icons">interventions</i>
                <p>{{ __('Interventions') }}</p>
            </a>
        </li>

        @if(isset($group_id))
            <li class="nav-item{{ $activePage == 'group' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('group', [$group_id]) }}">
                    <i class="material-icons">group</i>
                    @if(isset($week))
                        <p>{{ __('Group ' . $group_id . ': Week ' . $week) }}</p>
                    @elseif(isset($week_id))
                        <p>{{ __('Group ' . $group_id . ': Week ' . $week_id) }}</p>
                    @else
                        <p>{{ __('Group ' . $group_id) }}</p>
                    @endif
                </a>
            </li>
        @endif
        <li class="nav-item{{ $activePage == 'userList' ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('studentList', [$edition_id]) }}">
                <i class="material-icons">S</i>
                <p>{{ __('UserList') }}</p>
            </a>
        </li>
        <li class="nav-item{{ $activePage == 'assignTaToGroups' ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('assignTaToGroups', [$edition_id]) }}">
                <i class="material-icons">TA</i>
                <p>{{ __('Assign TA to Groups') }}</p>
            </a>
        </li>

        <li class="nav-item{{ $activePage == 'import' ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('importTAsStudents', [$edition_id]) }}">
                <i class="material-icons">mp</i>
                <p>{{ __('Import TAs/Students CSVs') }}</p>
            </a>
        </li>


    </ul>
  </div>
</div>
