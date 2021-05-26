<div class="wrapper ">
    @include('layouts.navbars.sidebarTA')
    <div class="main-panel">
        @include('layouts.navbars.navs.auth')
        @yield('content')
        @include('layouts.footers.auth')
        @yield('footer')
    </div>
</div>
