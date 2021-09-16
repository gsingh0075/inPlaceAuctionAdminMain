<!-- Contract Authorization -->
    <section id="assignment-item-authorization">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Item Authorization</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table dataTable zero-configuration">
                                    <thead>
                                    <tr>
                                        <th>Authorization ID</th>
                                        <th>Created Date</th>
                                        <th>Contractor</th>
                                        <th>Number of Items</th>
                                        <th>Send Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if( (!empty($contractorData)) && (count($contractorData) > 0))
                                        @foreach( $contractorData as $contractor )
                                            <tr>
                                                <td>{{ $contractor->contractor_auth_id }}</td>
                                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $contractor->create_dt)->format('j F, Y') }} </td>
                                                <td>{{ $contractor->contractor->first_name }} {{ $contractor->contractor->last_name }}</td>
                                                <td>{{ count($contractor->authItems) }}</td>
                                                <td>
                                                    @if(!empty($contractor->sent_date))
                                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $contractor->sent_date)->format('j F, Y')}}
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('viewContractorAuthorization', [$contractor->contractor_auth_id, $assignment->assignment_id] ) }}">View</a>/
                                                    @if($contractor->email_sent === 1)
                                                        <a href="javascript:void(0)"
                                                           class="sendContractorAuthorization"
                                                           data-attr-link="{{ route('sendContractorAuthorization', [$contractor->contractor_auth_id, $assignment->assignment_id] ) }}">ReSend</a>
                                                    @else
                                                        <a href="javascript:void(0)"
                                                           class="sendContractorAuthorization"
                                                           data-attr-link="{{ route('sendContractorAuthorization', [$contractor->contractor_auth_id, $assignment->assignment_id] ) }}">Send</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">No Authorizations</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<!-- End Contract Authorization -->
