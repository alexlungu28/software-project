<div class="wrapper ">
    <?php
    $role = DB::table('course_edition_user')
            ->where('user_id', '=', Auth::id())
            ->where('course_edition_id', '=', $edition_id)->first()->role;
    ?>
    @if(isset($role))
            @if ($role == 'TA')
                @include('layouts.navbars.sidebarTA')
            @elseif ($role == 'lecturer' || $role == 'HeadTA')
                @include('layouts.navbars.sidebar')
            @endif
    @endif
  <div class="main-panel">
    @include('layouts.navbars.navs.auth')
    @yield('content')
  </div>
</div>
