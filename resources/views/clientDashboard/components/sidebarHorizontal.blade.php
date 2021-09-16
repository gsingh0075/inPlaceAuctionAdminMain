<!-- BEGIN: Main Menu-->
<div class="header-navbar navbar-expand-sm navbar navbar-horizontal navbar-sticky navbar-dark navbar-without-dd-arrow" role="navigation" data-menu="menu-wrapper">
    <div class="navbar-header d-xl-none d-block">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <div class="brand-logo">
                        <img class="logo" src="{{ asset('app-assets/images/logo/logo.png') }}" alt="InPlaceAuction Logo"  />
                    </div>
                </a>
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
                    <i class="bx bx-x d-block d-xl-none font-medium-4 primary toggle-icon"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <!-- Horizontal menu content-->
    <div class="navbar-container main-menu-content" data-menu="menu-container">
        <!-- include ../../../includes/mixins-->
        <ul class="nav navbar-nav" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="lines">

            <!-- HOME -->
            <li class="nav-item" data-menu="dropdown">
                <a class="dropdown-toggle nav-link" href="{{ route('homePageClient') }}">
                    <i class="bx bxs-bar-chart-alt-2"></i>
                    <span class="menu-title" data-i18n="desktop">Dashboard</span>
                </a>
            </li>
            <!-- HOME -->
            <!-- Assignments Menu -->
            <li class="dropdown nav-item {{ request()->is('assignmentClient/*') ? 'active' : '' }}" data-menu="dropdown">
                <a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown">
                    <i class="bx bx-folder-open"></i>
                    <span class="menu-title" data-i18n="User">Assignment</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->is('assignmentClient/get') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('getAssignmentClient') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">List</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('assignmentClient/add') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('addNewAssignment') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">Add</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Assignments Menu End -->
            <!-- FMV Menu -->
            <li class="dropdown nav-item {{ request()->is('fmvClient/*') ? 'active' : '' }}" data-menu="dropdown">
                <a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown">
                    <i class="bx bx-file"></i>
                    <span class="menu-title" data-i18n="User">FMV</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->is('fmvClient/get') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('getFmvClient') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">List</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('fmvClient/add') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('addFmvClient') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">Rapid FMV</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- FMV Menu End -->
            <!-- Items Menu -->
            <li class="dropdown nav-item {{ request()->is('itemClient/*') ? 'active' : '' }}" data-menu="dropdown">
                <a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown">
                    <i class="bx bxs-collection"></i>
                    <span class="menu-title" data-i18n="User">Items</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->is('itemClient/get') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('getItemsClient') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">List</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Items Menu End -->
            <!-- HOME -->
            <li class="nav-item" data-menu="dropdown">
                <a class="dropdown-toggle nav-link" href="{{ route('client.logoUpload') }}">
                    <i class="bx bxs-categories"></i>
                    <span class="menu-title" data-i18n="desktop">Logo Management</span>
                </a>
            </li>
            <!-- HOME -->
            <!-- Contact Us Link -->
            <li class="nav-item" data-menu="dropdown">
                <a class="dropdown-toggle nav-link" href="mailto:sales@inplaceauction.com">
                    <i class="bx bxs-envelope"></i>
                    <span class="menu-title" data-i18n="desktop">Contact Us</span>
                </a>
            </li>
            <!-- End Contact Us Link -->
        </ul>
    </div>
    <!-- /horizontal menu content-->
</div>
<!-- END: Main Menu-->
