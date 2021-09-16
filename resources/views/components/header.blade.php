<!-- BEGIN: Header-->
<div class="header-navbar-shadow"></div>
<nav class="header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top ">
    <div class="navbar-wrapper">
        <div class="navbar-container content">
            <div class="navbar-collapse" id="navbar-mobile">
                <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                    <ul class="nav navbar-nav">
                        <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon bx bx-menu"></i></a></li>
                    </ul>
                </div>
                @if(auth()->user())
                    @php  $notifications = auth()->user()->unreadNotifications; @endphp
                @endif
                <ul class="nav navbar-nav float-right">
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
                    <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                            <div class="user-nav d-sm-flex d-none"><span class="user-name"> {{ Auth::user()->name }}</span></div>
                            <span class="d-md-none"> {{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right pb-0">
                            <a class="dropdown-item" href="#"><i class="bx bx-user mr-50"></i> Edit Profile</a>
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
