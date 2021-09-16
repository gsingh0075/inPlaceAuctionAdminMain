<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header" style="background: #fff">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <div class="brand-logo">
                        <img class="logo" src="{{ asset('app-assets/images/logo/logo.png') }}" alt="InPlaceAuction Logo"  />
                    </div>
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content mt-2">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="lines">
            <!-- HOME -->
            <li class=" nav-item active">
                    <a href="{{ route('home') }}"><i class="menu-livicon" data-icon="desktop"></i>
                        <span class="menu-title" data-i18n="desktop">Dashboard</span>
                    </a>
            </li>
            <!-- HOME -->
            <!-- CLIENTS -->
            <li class="nav-item {{ request()->is('client/*') ? 'active' : '' }}">
                   <a href="#">
                       <i class="menu-livicon" data-icon="users"></i>
                       <span class="menu-title" data-i18n="User">Clients</span>
                   </a>
                <ul class="menu-content">
                    <li class="{{ request()->is('client/get') ? 'active' : '' }}">
                        <a href="{{ route('getClients') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="View">List</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('client/add') ? 'active' : '' }}">
                        <a href="{{ route('addClient') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Edit">Add</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- CLIENTS -->
            <!-- FMV MENU -->
            <li class="nav-item {{ request()->is('fmv/*') ? 'active' : '' }}">
                     <a href="#">
                           <i class="menu-livicon" data-icon="notebook"></i>
                            <span class="menu-title" data-i18n="User">FMV</span>
                     </a>
                <ul class="menu-content">
                    <li class="{{ request()->is('fmv/get') ? 'active' : '' }}">
                        <a href="{{ route('getFmv') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">List</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('fmv/add') ? 'active' : '' }}">
                        <a href="{{ route('addFmv') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="View">Add</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- END FMV -->
            <!-- Assignments Menu -->
            <li class="nav-item {{ request()->is('assignment/*') ? 'active' : '' }}">
                <a href="#">
                    <i class="menu-livicon" data-icon="notebook"></i>
                    <span class="menu-title" data-i18n="User">Assignment</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ request()->is('assignment/get') ? 'active' : '' }}">
                        <a href="{{ route('getAssignment') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">List</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Assignments Menu End -->
            <!-- Contractors Menu -->
            <li class="nav-item {{ request()->is('contractor/*') ? 'active' : '' }}">
                <a href="#">
                    <i class="menu-livicon" data-icon="notebook"></i>
                    <span class="menu-title" data-i18n="User">Contractor</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ request()->is('contractor/get') ? 'active' : '' }}">
                        <a href="{{ route('getContractor') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">List</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Contractors Menu End -->
        </ul>
    </div>
</div>
<!-- END: Main Menu-->
