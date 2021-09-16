<!-- Lets End Tab Layout here -->
<div class="card-content">
    <div class="card-body">
        <form class="form" id="assignmentUpdateData" action="#" method="post"
              enctype="multipart/form-data">
            <input type="hidden" name="assignment_id"
                   value="{{ $assignment->assignment_id }}">
            <div class="form-body">
                <div class="row mt-2">
                    <div class="col-12">
                        <h6 class="py-50">Client Details</h6>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-3 col-12">
                                <b>Client's Name</b>
                            </div>
                            <div class="col-md-9 col-12">
                                @if(isset($assignment->client->clientInfo)) {{ $assignment->client->clientInfo->FIRSTNAME }} {{ $assignment->client->clientInfo->LASTNAME }} @endif
                            </div>
                            <div class="col-md-3 col-12">
                                <b>Client's Company</b>
                            </div>
                            <div class="col-md-9 col-12">
                                @if(isset($assignment->client->clientInfo)) {{ $assignment->client->clientInfo->COMPANY }} @endif
                            </div>
                            <div class="col-md-3 col-12">
                                <b>Client's Email</b>
                            </div>
                            <div class="col-md-9 col-12">
                                @if(isset($assignment->client->clientInfo)) {{ $assignment->client->clientInfo->EMAIL }} @endif
                            </div> <div class="col-md-3 col-12">
                                <b>Client's Phone</b>
                            </div>
                            <div class="col-md-9 col-12">
                                @if(isset($assignment->client->clientInfo)) {{ $assignment->client->clientInfo->PHONE }} @endif
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Lease Information -->
                <div class="row mt-2">
                    <div class="col-12">
                        <h6 class="py-50">Lease Information</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-12">
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <label for="ls_full_name">Lessees's Full Name</label>
                            </div>
                            <div class="col-md-8 form-group required col-12">
                                <input type="text" id="ls_full_name" class="form-control"
                                       name="ls_full_name" placeholder="Full Name"
                                       value="{{ $assignment->ls_full_name }}">
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="ls_company">Lessees's Company Name</label>
                            </div>
                            <div class="col-md-8 form-group required col-12">
                                <input type="text" id="ls_company" class="form-control"
                                       name="ls_company" placeholder="Company Name"
                                       value="{{ $assignment->ls_company }}">
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="ls_address1">Lessees's Address</label>
                            </div>
                            <div class="col-md-8 form-group required col-12">
                                <input type="text" id="ls_address1" class="form-control"
                                       name="ls_address1" placeholder="Address"
                                       value="{{ $assignment->ls_address1 }}">
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="ls_city">Lessees's City</label>
                            </div>
                            <div class="col-md-8 form-group required col-12">
                                <input type="text" id="ls_city" class="form-control"
                                       name="ls_city" placeholder="City"
                                       value="{{ $assignment->ls_city }}">
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="ls_state">Lessees's State</label>
                            </div>
                            <div class="col-md-8 form-group required col-12">
                                <input type="text" id="ls_state" class="form-control"
                                       name="ls_state" placeholder="State"
                                       value="{{ $assignment->ls_state }}">
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="ls_zip">Lessees's Zip</label>
                            </div>
                            <div class="col-md-8 form-group required col-12">
                                <input type="text" id="ls_zip" class="form-control"
                                       name="ls_zip" placeholder="Zip"
                                       value="{{ $assignment->ls_zip }}">
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="ls_buss_phone">Lessees's Business Phone</label>
                            </div>
                            <div class="col-md-8 form-group required col-12">
                                <input type="text" id="ls_buss_phone" class="form-control"
                                       name="ls_buss_phone" placeholder="Phone"
                                       value="{{ $assignment->ls_buss_phone }}">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Lease Information -->

                <!-- Lease Information -->
                <div class="row mt-2">
                    <div class="col-12">
                        <h6 class="py-50">Lease Details</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-12">
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <label for="lease_numbr">Lease Number</label>
                            </div>
                            <div class="col-md-8 form-group required col-12">
                                <input type="text" id="lease_numbr" class="form-control"
                                       name="lease_numbr" placeholder="Lease Number"
                                       value="{{ $assignment->lease_nmbr }}">
                            </div>

                            <div class="col-md-4 col-12">
                                <label for="dt_lease_inception_month">Date of Lease
                                    Inception</label>
                            </div>
                            <div class="col-md-4 form-group col-12">
                                <select class="custom-select form-control"
                                        name="dt_lease_inception_month"
                                        id="dt_lease_inception_month">
                                    <!-- Dynamically we will load -->
                                    <option value="0">Select Month</option>
                                    @foreach($months as $key => $val)
                                        @if(empty($assignment->dt_lease_inception))
                                            <option value="{{ $key }}">{{ $val }}</option>
                                        @else
                                            <option value="{{ $key }}"
                                                    @if($key == \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $assignment->dt_lease_inception)->format('m')) selected @endif>{{ $val }}</option>
                                        @endif

                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group col-12">
                                <select class="custom-select form-control"
                                        name="dt_lease_inception_year"
                                        id="dt_lease_inception_year">
                                    @php
                                        $latestYear = date('Y');
                                        $earliestYear = 1980;
                                    @endphp
                                    <option value="">Select Year</option>
                                    @foreach( range( $latestYear, $earliestYear ) as $i  )
                                        @if(empty($assignment->dt_lease_inception))
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @else
                                            <option value="{{ $i }}"
                                                    @if($i == \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $assignment->dt_lease_inception)->format('Y')) selected @endif>{{ $i }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="lease_term">Lease Term</label>
                            </div>
                            <div class="col-md-8 form-group col-12">
                                <input type="text" id="lease_term" class="form-control"
                                       name="lease_term" placeholder="Lease Term"
                                       value="{{ $assignment->lease_term }}">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Lease Information -->
                <!-- Assignment Information -->
                <div class="row mt-2">
                    <div class="col-12">
                        <h6 class="py-50">Assignment Information</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-12">
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <label for="dt_stmp">Assignment date</label>
                            </div>
                            <div class="col-md-8 form-group col-12">
                                <input type="text" id="dt_stmp" class="form-control"
                                       name="dt_stmp" placeholder="Date"
                                       value="{{ $assignment->dt_stmp }}">
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="lst_upd">Assignment Last Updated</label>
                            </div>
                            <div class="col-md-8 form-group col-12">
                                <input type="text" id="lst_upd" class="form-control"
                                       name="lst_upd" placeholder="Date"
                                       value="{{ $assignment->lst_upd }}">
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="isopen">Assignment Status</label>
                            </div>
                            <div class="col-md-8 form-group col-12">
                                <select class="custom-select form-control" name="isopen"
                                        id="isopen">
                                    <option value="1"
                                            @if($assignment->isopen == 1) selected @endif>
                                        Open
                                    </option>
                                    <option value="0"
                                            @if($assignment->isopen == 0) selected @endif>
                                        Close
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="approved">Approval Status</label>
                            </div>
                            <div class="col-md-8 form-group col-12">
                                <select class="custom-select form-control" name="approved"
                                        id="approved">
                                    <option value="1"
                                            @if($assignment->active == 1) selected @endif>
                                        Approved
                                    </option>
                                    <option value="0"
                                            @if($assignment->active == 0) selected @endif>
                                        Not Approved
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="recovered">Recovered ?</label>
                            </div>
                            <div class="col-md-8 form-group col-12">
                                <select class="custom-select form-control" name="recovered"
                                        id="recovered">
                                    <option value="1"
                                            @if($assignment->recovered == 1) selected @endif>
                                        Recovered
                                    </option>
                                    <option value="0"
                                            @if($assignment->recovered == 0) selected @endif>
                                        Not Recovered
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="recovery_month">Recovery Date</label>
                            </div>
                            <div class="col-md-3 form-group col-12">
                                <select class="custom-select form-control"
                                        name="recovery_month" id="recovery_month">
                                    <option value="0">Month</option>
                                    @foreach($months as $key => $val)
                                        @if(empty($assignment->recovery_dt))
                                            <option value="{{ $key }}">{{ $val }}</option>
                                        @else
                                            <option value="{{ $key }}"
                                                    @if($key == \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $assignment->recovery_dt)->format('m')) selected @endif>{{ $val }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 form-group col-12">
                                <select class="custom-select form-control"
                                        name="recovery_day">
                                    <option value="">Day</option>
                                    @for($m=1; $m<=31; $m++)
                                        @if(!empty($assignment->recovery_dt))
                                            <option value="{{ $m }}"
                                                    @if($m == \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $assignment->recovery_dt)->format('d')) selected @endif>{{ $m }}</option>
                                        @else
                                            <option value="{{ $m }}">{{ $m }}</option>
                                        @endif
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2 form-group col-12">
                                <select class="custom-select form-control"
                                        name="recovery_year" id="recovery_year">
                                    <option value="">Year</option>
                                    @foreach( range( $latestYear, $earliestYear ) as $i  )
                                        @if(empty($assignment->recovery_dt))
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @else
                                            <option value="{{ $i }}"
                                                    @if($i == \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $assignment->recovery_dt)->format('Y')) selected @endif>{{ $i }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="client_note">Client Notes</label>
                            </div>
                            <div class="col-md-8 form-group col-12">
                                                            <textarea
                                                                      class="form-control char-textarea"
                                                                      name="client_note" id="client_note" rows="3"
                                                                      placeholder="Client notes">{{ $assignment->clients_note }}</textarea>
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="voluntary">Voluntary ?</label>
                            </div>
                            <div class="col-md-8 form-group col-12">
                                <select class="custom-select form-control" name="voluntary"
                                        id="voluntary">
                                    <option value="1"
                                            @if($assignment->voluntary == 1) selected @endif>
                                        Voluntary
                                    </option>
                                    <option value="0"
                                            @if($assignment->voluntary == 0) selected @endif>
                                        InVoluntary
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="res_repo">Reason </label>
                            </div>
                            <div class="col-md-4 form-group col-12">
                                <fieldset>
                                    <div class="checkbox">
                                        <input type="checkbox" class="form-control"
                                               value="1"
                                               @if($assignment->res_repo == 1) checked
                                               @endif name="res_repo" id="res_repo">
                                        <label for="res_repo">Repossession</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-4 form-group col-12">
                                <fieldset>
                                    <div class="checkbox">
                                        <input type="checkbox" class="form-control"
                                               value="1"
                                               @if($assignment->res_coll == 1) checked
                                               @endif name="res_coll" id="res_coll">
                                        <label for="res_coll">Collection</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-4 form-group col-12 offset-md-4">
                                <fieldset>
                                    <div class="checkbox">
                                        <input type="checkbox" class="form-control"
                                               value="1"
                                               @if($assignment->res_skip == 1) checked
                                               @endif name="res_skip" id="res_skip">
                                        <label for="res_skip">Skip Trace</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-4 form-group col-12">
                                <fieldset>
                                    <div class="checkbox">
                                        <input type="checkbox" class="form-control"
                                               value="1"
                                               @if($assignment->res_rmkt == 1) checked
                                               @endif name="res_rmkt" id="res_rmkt">
                                        <label for="res_rmkt">Remarketing</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-4 form-group col-12 offset-md-4">
                                <fieldset>
                                    <div class="checkbox">
                                        <input type="checkbox" class="form-control"
                                               value="1"
                                               @if($assignment->res_app == 1) checked
                                               @endif name="res_app" id="res_app">
                                        <label for="res_app">Appraisal</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-4 form-group col-12">
                                <fieldset>
                                    <div class="checkbox">
                                        <input type="checkbox" class="form-control"
                                               value="1"
                                               @if($assignment->res_fmv == 1) checked
                                               @endif name="res_fmv" id="res_fmv">
                                        <label for="res_fmv">Fair Market Value</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-4 form-group col-12 offset-md-4">
                                <fieldset>
                                    <div class="checkbox">
                                        <input type="checkbox" class="form-control"
                                               value="1"
                                               @if($assignment->res_drive == 1) checked
                                               @endif name="res_drive" id="res_drive">
                                        <label for="res_drive">Drive by Research</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-4 form-group col-12">
                                <fieldset>
                                    <div class="checkbox">
                                        <input type="checkbox" class="form-control"
                                               value="1"
                                               @if($assignment->res_inv == 1) checked
                                               @endif name="res_inv" id="res_inv">
                                        <label for="res_inv">Investigation</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-4 form-group col-12 offset-md-4">
                                <fieldset>
                                    <div class="checkbox">
                                        <input type="checkbox" class="form-control"
                                               value="1"
                                               @if($assignment->res_knock == 1) checked
                                               @endif name="res_knock" id="res_knock">
                                        <label for="res_knock">Knock and Demand</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-4 form-group col-12">
                                <fieldset>
                                    <div class="checkbox">
                                        <input type="checkbox" class="form-control"
                                               value="1"
                                               @if($assignment->res_ins == 1) checked
                                               @endif name="res_ins" id="res_ins">
                                        <label for="res_ins">Inspection</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-8 form-group col-12 offset-md-4">
                                <fieldset>
                                    <div class="checkbox">
                                        <input type="checkbox" class="form-control"
                                               value="1"
                                               @if($assignment->res_eol == 1) checked
                                               @endif name="res_eol" id="res_eol">
                                        <label for="res_eol">End of Lease</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-4 col-12">
                                <label>Prior Contact Status </label>
                            </div>
                            <div class="col-md-8 form-group col-12">
                                <fieldset>
                                    <div class="radio">
                                        <input type="radio" class="form-control" value="1"
                                               @if($assignment->prior_contact == 1) checked
                                               @endif name="make_prior_contact" id="yes_prior">
                                        <label for="yes_prior">Make Prior Contact</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-8 form-group col-12 offset-md-4">
                                <fieldset>
                                    <div class="radio">
                                        <input type="radio" class="form-control" value="0"
                                               @if($assignment->prior_contact == 0) checked
                                               @endif name="make_prior_contact" id="no_prior">
                                        <label for="no_prior">Visit Unannounced/Do not make
                                            Prior Contact</label>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Assignment Information -->
                <div class="row mt-2">
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary mr-1 mb-1"
                                id="updateAssignmentBtn">Update Assignment
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>