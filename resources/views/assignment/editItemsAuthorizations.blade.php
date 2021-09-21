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
                                                    <a href="{{ route('viewContractorAuthorization', [$contractor->contractor_auth_id, $assignment->assignment_id] ) }}">View</a><br>
                                                    @if($contractor->email_sent === 1)
                                                        <a href="javascript:void(0)"
                                                           class="sendContractorAuthorization"
                                                           data-attr-link="{{ route('sendContractorAuthorization', [$contractor->contractor_auth_id, $assignment->assignment_id] ) }}">ReSend</a>
                                                    @else
                                                        <a href="javascript:void(0)"
                                                           class="sendContractorAuthorization"
                                                           data-attr-link="{{ route('sendContractorAuthorization', [$contractor->contractor_auth_id, $assignment->assignment_id] ) }}">Send</a>
                                                    @endif
                                                    <br>
                                                    <a href="javascript:void(0)" data-toggle="modal"
                                                       data-target="#AuthItemRecoveryModal_{{ $contractor->contractor_auth_id }}">Update Item Recovery Date</a>
                                                </td>
                                            </tr>
                                            <!-- Item Recovery Date Modal -->
                                            <div class="modal fade text-left" id="AuthItemRecoveryModal_{{ $contractor->contractor_auth_id }}" data-backdrop="static"
                                                 data-keyboard="false" tabindex="-1" role="dialog"
                                                 aria-labelledby="AuthItemRecoveryModal_{{ $contractor->contractor_auth_id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-xl modal-dialog-centered"
                                                     role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="myModalLabel33">Update Item(s) Recovery Date</h4>
                                                            <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                <i class="bx bx-x"></i>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12 py-2 filtersContainer">
                                                                        @if(isset($contractor->authItems) && !empty($contractor->authItems))
                                                                            @foreach($contractor->authItems as $i)
                                                                            <div class="row p-2">
                                                                                <div class="col-md-4 col-12">
                                                                                    <label for="Auth_item_name">{{ $i->item->ITEM_MAKE }} {{ $i->item->ITEM_MODEL }} {{ $i->item->ITEM_YEAR }} - {{ $i->item->ITEM_SERIAL }}</label>
                                                                                </div>
                                                                                <div class="col-md-5 col-12">
                                                                                    @if(empty($i->item->ITEM_RECOVERY_DT))
                                                                                    <input type="text" name="auth_item[]" data-item="{{ $i->item->ITEM_ID }}" class="form-control item_recovery_date_pick">
                                                                                     @else
                                                                                        <input type="text" name="auth_item[]" data-item="{{ $i->item->ITEM_ID }}" class="form-control item_recovery_date_pick" value="{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $i->item->ITEM_RECOVERY_DT)->format('j F, Y') }}">
                                                                                     @endif
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                            @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light-secondary"
                                                                    data-dismiss="modal">
                                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                                <span class="d-none d-sm-block">Close</span>
                                                            </button>
                                                            <button type="button" class="btn btn-light-secondary"
                                                                    data-action="{{ route('updateRecoveryDate') }}" id="updateRecoveryDateBtn">
                                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                                <span class="d-none d-sm-block">Update Dates</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Item Recovery Date Modal -->
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
