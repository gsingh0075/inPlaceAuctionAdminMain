<div class="card-body p-0 pb-1">
    <ul class="list-group list-group-flush" id="listAnalyticsData">
        <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
            <div class="list-left d-flex">
                <div class="list-icon mr-1">
                    <div class="avatar bg-rgba-primary m-0">
                        <div class="avatar-content">
                            <i class="bx bx-user text-primary font-size-base"></i>
                        </div>
                    </div>
                </div>
                <div class="list-content">
                    <span class="list-title">Total New Accounts</span>
                </div>
            </div>
            <span>{{ $data['totalAssignments'] }}</span>
        </li>
        <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
            <div class="list-left d-flex">
                <div class="list-icon mr-1">
                    <div class="avatar bg-rgba-primary m-0">
                        <div class="avatar-content">
                            <i class="bx bx-user text-primary font-size-base"></i>
                        </div>
                    </div>
                </div>
                <div class="list-content">
                    <span class="list-title">Approved Accounts</span>
                </div>
            </div>
            <span>{{  $data['approvedAssignments'] }}</span>
        </li>
        <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
            <div class="list-left d-flex">
                <div class="list-icon mr-1">
                    <div class="avatar bg-rgba-primary m-0">
                        <div class="avatar-content">
                            <i class="bx bx-user text-primary font-size-base"></i>
                        </div>
                    </div>
                </div>
                <div class="list-content">
                    <span class="list-title">Active Accounts</span>
                </div>
            </div>
            <span>{{ $data['activeAssignments'] }}</span>
        </li>
        <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
            <div class="list-left d-flex">
                <div class="list-icon mr-1">
                    <div class="avatar bg-rgba-primary m-0">
                        <div class="avatar-content">
                            <i class="bx bx-user text-primary font-size-base"></i>
                        </div>
                    </div>
                </div>
                <div class="list-content">
                    <span class="list-title">Open Accounts</span>
                </div>
            </div>
            <span>{{  $data['openAssignments'] }}</span>
        </li>
        <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
            <div class="list-left d-flex">
                <div class="list-icon mr-1">
                    <div class="avatar bg-rgba-primary m-0">
                        <div class="avatar-content">
                            <i class="bx bx-user text-primary font-size-base"></i>
                        </div>
                    </div>
                </div>
                <div class="list-content">
                    <span class="list-title">Closed Accounts</span>
                </div>
            </div>
            <span>{{ $data['closedAssignments'] }}</span>
        </li>
        <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
            <div class="list-left d-flex">
                <div class="list-icon mr-1">
                    <div class="avatar bg-rgba-primary m-0">
                        <div class="avatar-content">
                            <i class="bx bxs-zap text-primary font-size-base"></i>
                        </div>
                    </div>
                </div>
                <div class="list-content">
                    <span class="list-title">Total Assets</span>
                </div>
            </div>
            <span>{{  $data['totalAssets'] }}</span>
        </li><li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
            <div class="list-left d-flex">
                <div class="list-icon mr-1">
                    <div class="avatar bg-rgba-primary m-0">
                        <div class="avatar-content">
                            <i class="bx bxs-zap text-primary font-size-base"></i>
                        </div>
                    </div>
                </div>
                <div class="list-content">
                    <span class="list-title">Assets Sold</span>
                </div>
            </div>
            <span>{{ $data['assetsSold'] }}</span>
        </li>

        <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
            <div class="list-left d-flex">
                <div class="list-icon mr-1">
                    <div class="avatar bg-rgba-primary m-0">
                        <div class="avatar-content">
                            <i class="bx bx-dollar text-success text-primary font-size-base"></i>
                        </div>
                    </div>
                </div>
                <div class="list-content">
                    <span class="list-title">Expected EQ sales</span>
                </div>
            </div>
            <span>${{  number_format($data['expectedOlvValue'], 2) }}</span>
        </li>

        <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
            <!-- Empty -->
        </li>



        <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
            <div class="list-left d-flex">
                <div class="list-icon mr-1">
                    <div class="avatar bg-rgba-primary m-0">
                        <div class="avatar-content">
                            <i class="bx bx-stats text-primary font-size-base"></i>
                        </div>
                    </div>
                </div>
                <div class="list-content">
                    <span class="list-title">Fee Invoice Sent ( {{ $data['totalFeeInvoice'] }} )</span>
                </div>
            </div>
            <span>${{  number_format($data['feeInvoiceSent'], 2) }}</span>
        </li>
        <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
            <div class="list-left d-flex">
                <div class="list-icon mr-1">
                    <div class="avatar bg-rgba-primary m-0">
                        <div class="avatar-content">
                            <i class="bx bx-dollar text-success text-primary font-size-base"></i>
                        </div>
                    </div>
                </div>
                <div class="list-content">
                    <span class="list-title">Fee Invoice Paid</span>
                </div>
            </div>
            <span><span class="text-success">+</span>${{  number_format($data['feeInvoicePaid'], 2) }}</span>
        </li>

        <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
            <!-- Empty Row -->
        </li>
        <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
            <div class="list-left d-flex">
                <div class="list-icon mr-1">
                    <div class="avatar bg-rgba-primary m-0">
                        <div class="avatar-content">
                            <i class="bx bx-stats text-primary font-size-base"></i>
                        </div>
                    </div>
                </div>
                <div class="list-content">
                    <span class="list-title">EQ Invoice Sent ( {{ $data['totalCustomerInvoice'] }} )</span>
                </div>
            </div>
            <span>${{  number_format($data['customerInvoiceSent'], 2) }}</span>
        </li>
        <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
            <div class="list-left d-flex">
                <div class="list-icon mr-1">
                    <div class="avatar bg-rgba-primary m-0">
                        <div class="avatar-content">
                            <i class="bx bx-dollar text-success text-primary font-size-base"></i>
                        </div>
                    </div>
                </div>
                <div class="list-content">
                    <span class="list-title">EQ Invoice Paid</span>
                </div>
            </div>
            <span><span class="text-success">+</span>${{  number_format($data['customerInvoicePaid'], 2) }}</span>
        </li>

        <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
           <!-- Empty -->
        </li>
        <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
            <div class="list-left d-flex">
                <div class="list-icon mr-1">
                    <div class="avatar bg-rgba-primary m-0">
                        <div class="avatar-content">
                            <i class="bx bx-credit-card text-danger text-primary font-size-base"></i>
                        </div>
                    </div>
                </div>
                <div class="list-content">
                    <span class="list-title">Total Remittance Amount</span>
                </div>
            </div>
            <span><span class="text-danger">-</span>${{  number_format($data['clientRemittanceAmount'], 2) }}</span>
        </li>
        <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
            <div class="list-left d-flex">
                <div class="list-icon mr-1">
                    <div class="avatar bg-rgba-primary m-0">
                        <div class="avatar-content">
                            <i class="bx bx-dollar text-success text-primary font-size-base"></i>
                        </div>
                    </div>
                </div>
                <div class="list-content">
                    <h5> EQ Gross Commission</h5>
                </div>
            </div>
            <span class="text-success">${{number_format($data['commissionEarned'], 2) }}</span>
        </li>
        <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
            <div class="list-left d-flex">
                <div class="list-icon mr-1">
                    <div class="avatar bg-rgba-primary m-0">
                        <div class="avatar-content">
                            <i class="bx bx-dollar text-success text-primary font-size-base"></i>
                        </div>
                    </div>
                </div>
                <div class="list-content">
                    <h5>Total Profit</h5>
                </div>
            </div>
            <span class="text-success">${{number_format($data['profit'], 2) }}</span>
        </li>
    </ul>
</div>
