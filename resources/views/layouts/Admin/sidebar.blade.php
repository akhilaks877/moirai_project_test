<!-- Sidebar beginning -->
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
                <li class="app-sidebar__heading">{{__('menus.home')}}</li>
                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <span class="metismenu-icon pe-7s-home"></span>
                        {{__('menus.my_dashboard')}}
                    </a>
                </li>
                <li class="app-sidebar__heading">{{__('menus.library_management')}}</li>
                    <li>
                        <a href="{{ route('admin.title.index') }}">
                        <span class="metismenu-icon pe-7s-albums"></span>
                        {{__('menus.view_all_titles')}}
                        </a>
                    </li>
                    @foreach($random_books as $r_b)
                    <li>
                        <a href="#">
                            <span class="metismenu-icon pe-7s-notebook"></span>
                            {{ ucfirst($r_b->title) }}
                            <span class="metismenu-state-icon pe-7s-angle-down caret-left"></span>
                        </a>
                        <ul>
                            <li>
                                <a href="{{ route('admin.title.edit_book_detail',["id" => $r_b->id]) }}">
                                    {{__('menus.bibliographic_data')}}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.title.manage_book_note',["id" => $r_b->id]) }}">
                                    {{__('menus.manage_notes')}}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.title.manage_book_exercise',["id" => $r_b->id]) }}">
                                    {{__('menus.manage_exercises')}}
                                </a>
                            </li>
                            {{-- <li>
                                <a href="{{ route('admin.title.manage_webcontent') }}">
                                    {{__('menus.add_web_content')}}
                                </a>
                            </li> --}}
                        </ul>
                    </li>
                    @endforeach

                    <li class="app-sidebar__heading">{{__('menus.users')}}</li>
                <li class="{{ Request::segment(3) === 'student_details' ? 'mm-active' : null }}">
                    <a href="{{ route('student_details.index') }}">
                        <span class="metismenu-icon pe-7s-users"></span>
                        {{__('menus.students')}}
                    </a>
                </li>
                <li class="{{ Request::segment(3) === 'class_management' ? 'mm-active' : null }}">
                    <a href="{{ route('class_management.index') }}">
                        <span class="metismenu-icon pe-7s-display1"></span>
                        {{__('menus.classes')}}
                    </a>
                </li>
                <li class="{{ Request::segment(3) === 'teacher_details' ? 'mm-active' : null }}">
                    <a href="{{ route('teacher_details.index') }}">
                        <span class="metismenu-icon pe-7s-portfolio"></span>
                        {{__('menus.teachers')}}
                    </a>
                </li>
                <li class="{{ Request::segment(3) === 'school_management' ? 'mm-active' : null }}">
                    <a href="{{ route('school_management.index') }}">
                        <span class="metismenu-icon pe-7s-study"></span>
                        {{__('menus.schools')}}
                    </a>
                </li>
                <li class="{{ Request::segment(3) === 'admins' ? 'mm-active' : null }}">
                    <a href="{{ route('admins.index') }}">
                        <span class="metismenu-icon pe-7s-id"></span>
                        {{__('menus.administrators')}}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
    <!-- Sidebar end -->
