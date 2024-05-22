<!-- Left navbar links -->


<ul class="navbar-nav" style="display: flex; justify-content: center; align-items: center;">
    <li class="nav-item">
        <a id="hamburger-menu" class="nav-link" data-widget="pushmenu" href="#" role="button">
            <i class="fa fa-angle-double-left" style="font-size:22px"></i>
        </a>
    </li>
    <li class="nav-item" style="margin-left: auto; margin-right: auto;">
        {{-- <a href="#" class="nav-link">OLONGAPO NATIONAL HIGHSCHOOL</a> --}}
    </li>
</ul>

<!-- Right navbar links -->
<ul class="navbar-nav ml-auto flex">
    <!-- Navbar dropdown -->
    <li class="nav-item dropdown">
        <a  id="hamburger-menu" class="nav-link flex-right" data-widget="pushmenu" href="/view" role="button"><i class="fa fa-eye" style="font-size:22px"></i></a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ Auth::user()->name }}
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            @php
                $user = Auth::user();
                if ($user->hasRole('Super-admin')) {
                    $profileUrl = route('profile.super-admin', $user->id);
                } elseif ($user->hasRole('Admin')) {
                    $profileUrl = route('profile.admin', $user->id);
                } elseif ($user->hasRole('Offices')) {
                    $profileUrl = route('profile.offices', $user->id);
                } else {
                    $profileUrl = route('profile.super-admin', $user->id);
                }
            @endphp
            <a class="dropdown-item" href="{{ $profileUrl }}">Profile</a>
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Log Out') }}</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </li>
</ul>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<script>
    $(document).ready(function() {
        // Toggle sidebar menu
        $('#hamburger-menu').click(function() {
            $('body').toggleClass('sidebar-collapse');
        });
    });
</script>
