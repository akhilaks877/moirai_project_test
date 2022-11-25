<!-- Header beginning -->
<div class="app-header header-shadow">
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
    <div class="app-header__content">
        <div class="app-header-right">
            <div class="header-btn-lg pr-0">
                <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left">
                            <div class="btn-group">
                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn" role="button">
                                    @if (Auth::user()->user_img)
                                    <img width="42" class="rounded-circle" src="{{ asset('storage/user_profiles/'.Auth::user()->user_img) }}" alt="Your profile picture">
                                    @else
                                    <img width="42" class="rounded-circle" src="{{ asset('assets/images/avatars/placeholder_profile.jpg') }}" alt="Your profile picture">
                                    @endif
                                    <span class="fa fa-angle-down ml-2 opacity-8"></span>
                                </a>
                                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                    <a href="{{ route('student.accessibility.index') }}" tabindex="0" class="dropdown-item">Accessibility</a>
                                    <a href="{{ route('student.my-account.index') }}" tabindex="0" class="dropdown-item">My Account</a>
                                    <a href="{{ route('student.my-badges.index') }}" tabindex="0" class="dropdown-item">My Badges</a>
                                    <a href="{{ route('student.faq.index') }}" tabindex="1" class="dropdown-item">FAQ</a>
                                    <a href="{{ route('logout') }}" tabindex="1" class="dropdown-item" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{__('menus.logout')}}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content-left  ml-3 header-user-info">
                            <div class="widget-heading">
                                @if(auth()->user()->roles)
                                {{ auth()->user()->firstname }} {{ auth()->user()->lastname }}
							    @endif
                            </div>
                            <div class="widget-subheading">
                               Student
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Header end -->
