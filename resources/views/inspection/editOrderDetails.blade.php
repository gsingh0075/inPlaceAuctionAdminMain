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
                        <h6 class="py-50">Order Information</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-12">
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <label for="ls_full_name">Order's Full Name</label>
                            </div>
                            <div class="col-md-8 form-group required col-12">
                                <input type="text" id="ls_full_name" class="form-control"
                                       name="ls_full_name" placeholder="Full Name"
                                       value="{{ $assignment->ls_full_name }}">
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="ls_company">Order's Company Name</label>
                            </div>
                            <div class="col-md-8 form-group required col-12">
                                <input type="text" id="ls_company" class="form-control"
                                       name="ls_company" placeholder="Company Name"
                                       value="{{ $assignment->ls_company }}">
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="ls_address1">Order's Address</label>
                            </div>
                            <div class="col-md-8 form-group required col-12">
                                <input type="text" id="ls_address1" class="form-control"
                                       name="ls_address1" placeholder="Address"
                                       value="{{ $assignment->ls_address1 }}">
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="ls_city">Order's City</label>
                            </div>
                            <div class="col-md-8 form-group required col-12">
                                <input type="text" id="ls_city" class="form-control"
                                       name="ls_city" placeholder="City"
                                       value="{{ $assignment->ls_city }}">
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="ls_state">Order's State</label>
                            </div>
                            <div class="col-md-8 form-group required col-12">
                                <input type="text" id="ls_state" class="form-control"
                                       name="ls_state" placeholder="State"
                                       value="{{ $assignment->ls_state }}">
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="ls_zip">Order's Zip</label>
                            </div>
                            <div class="col-md-8 form-group required col-12">
                                <input type="text" id="ls_zip" class="form-control"
                                       name="ls_zip" placeholder="Zip"
                                       value="{{ $assignment->ls_zip }}">
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="ls_buss_phone">Order's Business Phone</label>
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
                        <h6 class="py-50">Order Details</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-12">
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <label for="lease_numbr">Reference Number</label>
                            </div>
                            <div class="col-md-8 form-group required col-12">
                                <input type="text" id="lease_numbr" class="form-control"
                                       name="lease_numbr" placeholder="Lease Number"
                                       value="{{ $assignment->lease_nmbr }}">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Lease Information -->
                <!-- Assignment Information -->
                <div class="row mt-2">
                    <div class="col-12">
                        <h6 class="py-50">Inspection Information</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-12">
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <label for="dt_stmp">Inspection Date</label>
                            </div>
                            <div class="col-md-8 form-group col-12">
                                <input type="text" id="dt_stmp" class="form-control"
                                       name="dt_stmp" placeholder="Date"
                                       value="{{ $assignment->dt_stmp }}">
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="lst_upd">Inspection Last Updated</label>
                            </div>
                            <div class="col-md-8 form-group col-12">
                                <input type="text" id="lst_upd" class="form-control"
                                       name="lst_upd" placeholder="Date"
                                       value="{{ $assignment->lst_upd }}">
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="isopen">Inspection Status</label>
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
                                <label for="client_note">Client Notes</label>
                            </div>
                            <div class="col-md-8 form-group col-12">
                                <textarea class="form-control char-textarea" name="client_note" id="client_note" rows="3"  placeholder="Client notes">{{ $assignment->clients_note }}</textarea>
                            </div>
                            <div class="col-md-4 col-12" style="display: none">
                                <label for="res_repo">Reason </label>
                            </div>
                            <div class="col-md-8 form-group col-12 offset-md-4" style="display:none">
                                <fieldset>
                                    <div class="checkbox">
                                        <input type="checkbox" class="form-control" value="1"  name="res_ins" id="res_ins" checked>
                                        <label for="res_ins">Inspection</label>
                                    </div>
                                    <select class="custom-select form-control" name="is_inspection" id="is_inspection">
                                        <option value="1" selected>Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                    <select class="custom-select form-control" name="is_appraisal" id="is_appraisal">
                                        <option value="1">Yes</option>
                                        <option value="0" selected>No</option>
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Assignment Information -->
                <div class="row mt-2">
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary mr-1 mb-1"
                                id="updateAssignmentBtn">Update Inspection
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
