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
            <li class="dropdown nav-item" data-menu="dropdown">
                <a class="dropdown-toggle nav-link" href="{{ route('home') }}">
                    <i class="bx bxs-bar-chart-alt-2"></i>
                    <span class="menu-title" data-i18n="desktop">Dashboard</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->is('yearComparisonChart') ? 'active' : '' }}">
                        <a  class="dropdown-item align-items-center" href="{{ route('yearComparisonChart') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="View">Years Comparison</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- HOME -->
            <!-- ACCOUNTING -->
            <li class="dropdown nav-item {{ request()->is('accounting/*') ? 'active' : '' }}" data-menu="dropdown">
                <a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown">
                    <i class="bx bx-money"></i>
                    <span class="menu-title" data-i18n="User">Accounting</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->is('accounting/getClientInvoices') ? 'active' : '' }}">
                        <a  class="dropdown-item align-items-center" href="{{ route('getAccountClientInvoices') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="View">Client Invoices</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('accounting/getClientReceivables') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('getAccountClientReceivables') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Edit">Client Receivables</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('accounting/getClientReceivableReport') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('getAccountClientReceivableReport') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Edit">Client Receivables Report</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('accounting/getCustomerInvoices') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('getAccountCustomerInvoices') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Edit">Customer Invoices</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('accounting/getCustomerReceivables') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('getAccountCustomerReceivables') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Edit">Customer Receivables</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('accounting/getClientRemittance') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('getAccountClientRemittance') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Edit">Client Remittance</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('accounting/getClientRemittanceReport') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('getClientRemittanceReport') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Edit">Client Remittance Report</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- CLIENTS -->
            <li class="dropdown nav-item {{ request()->is('client/*') ? 'active' : '' }}" data-menu="dropdown">
                <a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown">
                    <i class="bx bx-user"></i>
                    <span class="menu-title" data-i18n="User">Clients</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->is('client/get') ? 'active' : '' }}">
                        <a  class="dropdown-item align-items-center" href="{{ route('getClients') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="View">List</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('client/add') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('addClient') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Edit">Add</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('client/clientChat') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('clientChat') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Edit">Communication</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- CLIENTS -->
            <!-- CLIENTS -->
            <li class="dropdown nav-item {{ request()->is('customer/*') ? 'active' : '' }}" data-menu="dropdown">
                <a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown">
                    <i class="bx bx-user"></i>
                    <span class="menu-title" data-i18n="User">Customers</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->is('customer/getAll') ? 'active' : '' }}">
                        <a  class="dropdown-item align-items-center" href="{{ route('getAllCustomers') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="View">List</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('customer/add') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('addCustomer') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Edit">Add</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- CLIENTS -->
            <!-- FMV MENU -->
            <li class="dropdown nav-item {{ request()->is('fmv/*') ? 'active' : '' }}" data-menu="dropdown">
                <a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown">
                    <i class="bx bx-file"></i>
                    <span class="menu-title" data-i18n="User">FMV</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->is('fmv/get') ? 'active' : '' }}">
                        @php   $archiveYear =  \Carbon\Carbon::now()->subYears(2)->year; @endphp
                        <a class="dropdown-item align-items-center" href="{{ route('getFmv') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">List > {{ $archiveYear }}</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('fmv/getArchiveFMV') ? 'active' : '' }}">
                        @php   $archiveYear =  \Carbon\Carbon::now()->subYears(2)->year; @endphp
                        <a class="dropdown-item align-items-center" href="{{ route('getArchiveFMV') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">List < {{ $archiveYear }}</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('fmv/add') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('addFmv') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="View">Add</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- END FMV -->
            <!-- Assignments Menu -->
            <li class="dropdown nav-item {{ request()->is('assignment/*') ? 'active' : '' }}" data-menu="dropdown">
                <a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown">
                    <i class="bx bx-folder-open"></i>
                    <span class="menu-title" data-i18n="User">Assignment</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->is('assignment/get') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('getAssignment') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">List</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('/assignment/add') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('addNewAssignment') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">Add</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('/reAssignAssignments') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('reassignAssignment') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">Re Assign Assignment</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('/closedAssignments') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('closedAssignments') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">Closed</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Assignments Menu End -->
            <!-- Inspection Menu -->
            <li class="dropdown nav-item {{ request()->is('inspection/*') ? 'active' : '' }}" data-menu="dropdown">
                <a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown">
                    <i class="bx bx-folder-open"></i>
                    <span class="menu-title" data-i18n="User">Inspection</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->is('inspection/get') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('getInspection') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">List</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('/inspection/add') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('addNewInspection') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">Add</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('/inspection/reports') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('getInspectionReports') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">Reports</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Inspection End -->
            <!-- Equipments Listing -->
            <!-- Assignments Menu -->
            <li class="dropdown nav-item {{ request()->is('equipments/*') ? 'active' : '' }}" data-menu="dropdown">
                <a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown">
                    <i class="bx bxs-collection"></i>
                    <span class="menu-title" data-i18n="User">Equipment</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->is('equipments/listItems') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('listItems') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">Items</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('equipments/listBids') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('listBids') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">Bids</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Assignments Menu End -->
            <!-- Equipments Listing End -->
            <!-- Contractors Menu -->
            <li class="dropdown nav-item {{ request()->is('contractor/*') ? 'active' : '' }}" data-menu="dropdown">
                <a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown">
                    <i class="bx bx-user"></i>
                    <span class="menu-title" data-i18n="User">Contractor</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->is('contractor/get') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('getContractor') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">List</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('/contractor/add') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('addContractor') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">Add</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('/contractor/authList') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('getContractorAuthorizationList') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">Authorizations</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Contractors Menu End -->
            <!-- Tools Menu -->
            <li class="dropdown nav-item {{ request()->is('tools/*') ? 'active' : '' }}" data-menu="dropdown">
                <a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown">
                    <i class="bx bxs-categories"></i>
                    <span class="menu-title" data-i18n="User">Tools</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->is('tools/listCategories') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('listCategories') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">Category</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('tools/listExpense') ? 'active' : '' }}">
                        <a class="dropdown-item align-items-center" href="{{ route('listExpense') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="List">Expense Types</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- End Tools Menu -->
        </ul>
    </div>
    <!-- /horizontal menu content-->
</div>
<!-- END: Main Menu-->
