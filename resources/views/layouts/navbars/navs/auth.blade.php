<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
  <div class="container-fluid">

    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
    <span class="sr-only">Toggle navigation</span>
    <span class="navbar-toggler-icon icon-bar"></span>
    <span class="navbar-toggler-icon icon-bar"></span>
    <span class="navbar-toggler-icon icon-bar"></span>
    </button>
      <div class="collapse navbar-collapse justify-content-end">
{{--      <form class="navbar-form">--}}
{{--        <div class="input-group no-border">--}}
{{--        <input type="text" value="" class="form-control" placeholder="Search...">--}}
{{--        <button type="submit" class="btn btn-white btn-round btn-just-icon">--}}
{{--          <i class="material-icons">search</i>--}}
{{--          <div class="ripple-container"></div>--}}
{{--        </button>--}}
{{--        </div>--}}
{{--      </form>--}}
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('courses') }}">
            <i class="material-icons">home</i>
            <p class="d-lg-none d-md-block">
              {{ __('Stats') }}
            </p>
          </a>
        </li>
          <li class="nav-item dropdown">
          <a class="nav-link" href="" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="material-icons">notifications</i>
              @if(auth()->check())
                  @if(auth()->user() != null)
                      @if(count(auth()->user()->unreadNotifications) > 0)
                          <span class="notification">{{count(auth()->user()->unreadNotifications)}}</span>
                      @endif
                  @endif
              @endif
            <p class="d-lg-none d-md-block">
              {{ __('Some Actions') }}
            </p>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
              @if(auth()->check())
                  @if(auth()->user() != null)
                    @foreach(auth()->user()->unreadNotifications as $notification)
                        @if(isset($notification->data['Deadline passed']))
                            @php
                                $group_id = $notification->data['Deadline passed']['group_id'];
                                $group = App\Models\Group::find($group_id);
                                $edition = App\Models\CourseEdition::find($group->course_edition_id);
                                $course = App\Models\Course::find($edition->course_id);
                                $sameEdition = $edition->id == $edition_id;
                            @endphp
                            <a class="dropdown-item" href="{{route('group', $group_id)}}">
                                @if(!$sameEdition)
                                    {{ 'Individual intervention deadline passed: ' . $course->description . ', ' . $edition->year . ', ' . $group->group_name }}
                                @else
                                    {{ 'Individual intervention deadline passed: ' . $group->group_name }}
                                @endif
                            </a>
                        @elseif(isset($notification->data['Deadline passed group']))
                              @php
                                  $group_id = $notification->data['Deadline passed group']['group_id'];
                                  $group = App\Models\Group::find($group_id);
                                  $edition = App\Models\CourseEdition::find($group->course_edition_id);
                                  $course = App\Models\Course::find($edition->course_id);
                                  $sameEdition = $edition->id == $edition_id;
                              @endphp
                              <a class="dropdown-item" href="{{route('group', $group_id)}}">
                                  @if(!$sameEdition)
                                      {{ 'Group intervention deadline passed: ' . $course->description . ', ' . $edition->year . ', ' . $group->group_name }}
                                  @else
                                      {{ 'Group intervention deadline passed: ' . $group->group_name }}
                                  @endif
                              </a>
                        @endif
                    @endforeach
                  @endif
              @endif
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="material-icons">person</i>
            <p class="d-lg-none d-md-block">
              {{ __('Account') }}
            </p>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
            <a class="dropdown-item" href="{{ route('notificationSettings', [$edition_id]) }}">{{ __('Notification Settings') }}</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ route('logout') }}">{{ __('Log out') }}</a>
          </div>
        </li>
          <li class="nav-item">
              <a class="nav-link" id="darkMode" style="user-select: none">
                  <i id="themeIcon" class="material-icons">lightbulb</i>
              </a>
          </li>
      </ul>
    </div>
  </div>
</nav>
