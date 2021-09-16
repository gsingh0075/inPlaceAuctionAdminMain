<!-- BEGIN: Header-->
<nav class="header-navbar navbar-expand-lg navbar navbar-with-menu navbar-static-top bg-primary navbar-brand-center">
    <div class="navbar-header d-xl-block d-none">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <div class="brand-logo">
                        <img class="logo" src="{{ asset('app-assets/images/logo/logo_tr.png') }}" alt="InPlaceAuction Logo"  />
                    </div>
                </a>
            </li>
        </ul>
    </div>
    <div class="navbar-wrapper">
        <div class="navbar-container content">
            <div class="navbar-collapse" id="navbar-mobile">
                <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                    <ul class="nav navbar-nav">
                        <li class="nav-item mobile-menu mr-auto">
                            <a class="nav-link nav-menu-main menu-toggle" href="#">
                                <i class="bx bx-menu"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                @if(auth()->user())
                    @php  $notifications = auth()->user()->unreadNotifications; @endphp
                @endif
                <ul class="nav navbar-nav float-right d-flex align-items-center">
                    <li class="dropdown dropdown-theme nav-item">
                        <a class="nav-link nav-link-label themeToggle" @if($theme == 'dark') data-theme="light" @elseif($theme == 'light') data-theme="dark" @endif  href="javascript:void(0)">
                            <span class="user-name">Dark</span>
                            @if($theme == 'dark')
                            <i class="bx bxs-toggle-left" style="font-size: 20px;"></i>
                            @elseif($theme == 'light')
                             <i class="bx bx-toggle-right" style="font-size: 20px;"></i>
                            @endif
                            <span class="user-name"> Light</span>
                        </a>
                    </li>
                    <li class="dropdown dropdown-notification nav-item">
                        <a class="nav-link nav-link-label" href="#" data-toggle="dropdown">
                            <i class="ficon bx bx-bell bx-tada bx-flip-horizontal"></i>
                            <span class="badge badge-pill badge-danger badge-up">{{ count($notifications) }}</span>
                        </a>
                        @if(auth()->user())
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <div class="dropdown-header px-1 py-75 d-flex justify-content-between"><span class="notification-title">{{ count($notifications) }} new Notification</span></div>
                                </li>
                                @foreach($notifications as $notification)
                                    <li class="scrollable-container media-list"><a class="d-flex justify-content-between" href="{{ route('showNotification',['id' => $notification->id]) }}">
                                            <div class="media d-flex align-items-center">
                                                <div class="media-body">
                                                    <h6 class="media-heading"><span class="text-bold-500">{{$notification->data['type']}} </span> by {{$notification->data['by']}}  for {{ $notification->data['resource'] }}</h6>
                                                    <small class="notification-text">{{ $notification->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                    </li>
                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                            <div class="user-nav d-sm-flex d-none"><span class="user-name"> {{ Auth::user()->name }}</span></div>
                            <span class="d-md-none"> {{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right pb-0">
                            <a class="dropdown-item" href="{{ route('userChangePassword') }}"><i class="bx bx-user mr-50"></i>Update Password</a>
                            <div class="dropdown-divider mb-0"></div>
                            <a class="dropdown-item"
                               href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                                <i class="bx bx-power-off mr-50"></i> Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<!-- END: Header-->
