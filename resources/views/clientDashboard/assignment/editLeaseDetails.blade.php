<!-- Lets End Tab Layout here -->
<div class="card-content">
    <div class="card-body">
            <div class="form-body">
                <!-- Lease Information -->
                <div class="row">
                    <div class="col-md-8 col-12">
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <label for="lease_numbr">Lease Number</label>
                            </div>
                            <div disabled class="col-md-8 form-group col-12">
                               {{ $assignment->lease_nmbr }}
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="ls_company">Lessees's Company Name</label>
                            </div>
                            <div class="col-md-8 form-group col-12">
                                {{ $assignment->ls_company }}
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="ls_full_name">Lessees's Full Name</label>
                            </div>
                            <div class="col-md-8 form-group col-12">
                                {{ $assignment->ls_full_name }}
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="ls_address1">Lessees's Address</label>
                            </div>
                            <div class="col-md-8 form-group col-12">
                                {{ $assignment->ls_address1 }}
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="ls_city">Lessees's City</label>
                            </div>
                            <div class="col-md-8 form-group col-12">
                                {{ $assignment->ls_city }}
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="ls_state">Lessees's State</label>
                            </div>
                            <div class="col-md-8 form-group col-12">
                                {{ $assignment->ls_state }}
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="ls_zip">Lessees's Zip</label>
                            </div>
                            <div class="col-md-8 form-group col-12">
                                {{ $assignment->ls_zip }}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Lease Information -->
            </div>
    </div>
</div>