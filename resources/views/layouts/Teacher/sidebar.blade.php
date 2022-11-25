<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <span class="fa fa-ellipsis-v fa-w-6"></span>
                </span>
            </button>
        </span>
    </div>
    <div class="scrollbar-container ps--active-y ps"></div>
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">Home</li>
                <li class="mm-active">
                    <a href="{{ route('teacher.dashboard.index') }}" class="mm-active">
                        <span class="metismenu-icon pe-7s-home"></span>
                        My Dashboard
                    </a>
                </li>
                <li class="app-sidebar__heading">Library Management</li>
                    <li>
                        <a href="{{ route('teacher.title.index') }}">
                            <span class="metismenu-icon pe-7s-albums"></span>
                            View All Titles
                        </a>
                    </li>
                    @foreach($random_books as $r_b)
                    <li>
                        <a href="{{ route('teacher.title.reading_book',["title" => $r_b->id]) }}">
                            <span class="metismenu-icon pe-7s-notebook"></span>
                            {{ ucfirst($r_b->title) }}
                            <span class="metismenu-state-icon pe-7s-angle-down caret-left"></span>
                        </a>
                    </li>
                    @endforeach

                <li class="app-sidebar__heading">Classes</li>
                <li>
                    <a href="{{ route('teacher.my-classes.index') }}" class="{{ (Request::segment(2) === 'my-classes' && Request::segment(3) !== 'create') ? 'mm-active' : '' }}">
                        <span class="metismenu-icon pe-7s-users"></span>
                        My Classes
                    </a>
                </li>
                <li>
                    <a href="{{ route('teacher.my-classes.create') }}" class="{{ Request::segment(3) === 'create' ? 'mm-active' : '' }}">
                        <span class="metismenu-icon pe-7s-display1"></span>
                        Add a Class
                    </a>
                </li>
                <li>
                    <a href="{{route('teacher.student-request')}}">
                        <span class="metismenu-icon pe-7s-portfolio"></span>
                        Requests from Students
                    </a>
                </li>

                <li class="app-sidebar__heading">Statistics</li>
                <li>
                    <a href="{{route('teacher.statistics-teacher')}}">
                        <span class="metismenu-icon pe-7s-users"></span>
                        Platform Analytics
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
