@if (Auth::check() && Auth::user()->isAdmin())
    @include('widgets.navbar.adminNavBar')
@elseif (Auth::check())
    @include('widgets.navbar.userNavBar')
@else
    @include('widgets.navbar.loggedOutNavBar')
@endif