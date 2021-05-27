<div class="sidebar" data-color="orange" data-background-color="white" data-image="{{ asset('material') }}/img/sidebar-1.jpg">
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
        <a class="nav-link" href="{{ route('notifications') }}">
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
        <li class="nav-item{{ $activePage == 'studentList' ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('studentList', [$edition_id]) }}">
                <i class="material-icons">S</i>
                <p>{{ __('StudentList') }}</p>
            </a>
        </li>

        <li class="nav-item{{ $activePage == 'import' ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('importExport', [$edition_id]) }}">
                <i class="material-icons">mp</i>
                <p>{{ __('Import/Export') }}</p>
            </a>
        </li>


    </ul>
  </div>
</div>
