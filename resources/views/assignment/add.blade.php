@extends('layouts.masterHorizontal')

@section('title','Add Assignment - InPlace Auction')

@push('page-style')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/file-uploaders/dropzone.css') }}">
@endpush
@section('content')

    <div class="content-wrapper">
        <div class="content-header container">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Assignment</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('getAssignment') }}">List</a>
                                </li>
                                <li class="breadcrumb-item active">Add
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body container">
            <!-- Edit Assignment Form -->
            <section id="floating-label-layouts">
                <div class="row match-height">
                    <div class="col-12 addContainer" id="fmv-update-container">
                        <div class="card">
                           <!--<div class="card-header">
                            </div>-->
                            <div class="card-content">
                                <div class="card-body">
                                    <form class="form" id="assignmentAddData" action="#" method="post">
                                        <div class="form-body">
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <h6 class="py-50">Client Details</h6>
                                                </div>
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label for="client_id">Choose Client</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                          <select name="client_id" id="client_id" class="custom-select form-control">
                                                              @if(isset($clients) && !empty($clients))
                                                                  @foreach( $clients as $c)
                                                                      <option value="{{ $c->CLIENT_ID }}">{{ $c->FIRSTNAME }} {{ $c->LASTNAME }} {{ $c->COMPANY }}</option>
                                                                  @endforeach
                                                              @endif
                                                          </select>
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
                                                            <input type="text" id="ls_full_name" class="form-control" name="ls_full_name" placeholder="Full Name" value="">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="ls_company">Lessees's Company Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="ls_company" class="form-control" name="ls_company" placeholder="Company Name" value="">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="ls_address1">Lessees's Address</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="ls_address1" class="form-control" name="ls_address1" placeholder="Address" value="">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="ls_city">Lessees's City</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="ls_city" class="form-control" name="ls_city" placeholder="City" value="">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="ls_state">Lessees's State</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <select id="ls_state" name="ls_state" class="form-control custom-select">
                                                                @foreach( $states as $s )
                                                                    <option value="{{ $s->STATE_ID }}">{{ $s->STATE }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="ls_zip">Lessees's Zip</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="ls_zip" class="form-control" name="ls_zip" placeholder="Zip" value="">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="ls_buss_phone">Lessees's Business Phone</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="ls_buss_phone" class="form-control" name="ls_buss_phone" placeholder="Phone" value="">
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
                                                            <input type="text" id="lease_numbr" class="form-control" name="lease_numbr" placeholder="Lease Number" value="">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="dt_lease_inception_month">Date of Lease Inception</label>
                                                        </div>
                                                        <div class="col-md-4 form-group col-12">
                                                                <select class="custom-select form-control" name="dt_lease_inception_month" id="dt_lease_inception_month">
                                                                    <!-- Dynamically we will load -->
                                                                    <option value="0">Select Month</option>
                                                                    @foreach($months as $key => $val)
                                                                        <option value="{{ $key }}">{{ $val }}</option>
                                                                    @endforeach
                                                                </select>
                                                        </div>
                                                        <div class="col-md-4 form-group col-12">
                                                            <select class="custom-select form-control" name="dt_lease_inception_year" id="dt_lease_inception_year">
                                                                @php
                                                                    $latestYear = date('Y');
                                                                    $earliestYear = 1980;
                                                                @endphp
                                                                <option value="">Select Year</option>
                                                                @foreach( range( $latestYear, $earliestYear ) as $i  )
                                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="lease_term">Lease Term</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="lease_term" class="form-control" name="lease_term" placeholder="Lease Term" value="">
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
                                                            <label for="isopen">Assignment Status</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <select class="custom-select form-control" name="isopen" id="isopen">
                                                                <option value="1">Open</option>
                                                                <option value="0">Close</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="isopen">Is Appraisal</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <select class="custom-select form-control" name="is_appraisal" id="is_appraisal">
                                                                <option value="1">Yes</option>
                                                                <option value="0">No</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="approved">Approval Status</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <select class="custom-select form-control" name="approved" id="approved">
                                                                <option value="1">Approved</option>
                                                                <option value="0">Not Approved</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="client_note">Client Notes</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <textarea class="form-control char-textarea active" name="client_note" id="client_note" rows="3" placeholder="Client notes" style="color: rgb(48, 65, 86);"></textarea>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="res_repo">Reason </label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control" value="1" name="res_repo" id="res_repo">
                                                                    <label for="res_repo">Repossession</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control" value="1" name="res_coll" id="res_coll">
                                                                    <label for="res_coll">Collection</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control" value="1" name="res_skip" id="res_skip">
                                                                    <label for="res_skip">Skip Trace</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control" value="1" name="res_rmkt" id="res_rmkt">
                                                                    <label for="res_rmkt">Remarketing</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control" value="1" name="res_app" id="res_app">
                                                                    <label for="res_app">Appraisal</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control" value="1" name="res_fmv" id="res_fmv">
                                                                    <label for="res_fmv">Fair Market Value</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control" value="1" name="res_drive" id="res_drive">
                                                                    <label for="res_drive">Drive by Research</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control" value="1" name="res_inv" id="res_inv">
                                                                    <label for="res_inv">Investigation</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control" value="1" name="res_knock" id="res_knock">
                                                                    <label for="res_knock">Knock and Demand</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control" value="1"  name="res_ins" id="res_ins">
                                                                    <label for="res_ins">Inspection</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control" value="1" name="res_eol" id="res_eol">
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
                                                                    <input type="radio" class="form-control" value="1" name="make_prior_contact" id="yes_prior">
                                                                    <label for="yes_prior">Make Prior Contact</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="radio">
                                                                    <input type="radio" class="form-control" value="0" name="make_prior_contact" id="no_prior">
                                                                    <label for="no_prior">Visit Unannounced/Do not make Prior Contact</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Assignment Information -->

                                            <div class="row mt-2">
                                              <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary mr-1 mb-1" id="addAssignment">Add Assignment</button>
                                                <!--<button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>-->
                                              </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--Assignment Form Files -->
        </div>
    </div>
@push('page-vendor-js')
<script src="{{ asset('app-assets/vendors/js/extensions/dropzone.min.js') }}"></script>
@endpush
@push('page-js')
<script>

    var assignmentAddData = $('#assignmentAddData');

    $(document).ready(function(){

        $('#client_id').select2({
            placeholder: "Customer"
        });

        $('#ls_state').select2({
            placeholder: "State"
        });

        // Add Button Clicked.
          $('#addAssignment').click(function(e){

              e.preventDefault();
              blockExt(assignmentAddData, $('#waitingMessage'));

              $.ajax({
                  url: "{{route('saveNewAssignment')}}",
                  type: "POST",
                  dataType: "json",
                  data: assignmentAddData.serialize(),
                  headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                  success: function (response) {
                      if (response.status) {
                          //console.log(response)
                          Swal.fire({
                              title: "Good job!",
                              text: "Assignment added!",
                              type: "success",
                              confirmButtonClass: 'btn btn-primary',
                              buttonsStyling: false,
                          }).then(function (result) {
                              if (result.value) {
                                  console.log('Assignment Added. We will redirect to assignment');
                                  window.location = "{{ route('getAssignment') }}";
                              }
                              unBlockExt(assignmentAddData);
                          });
                      } else {
                          unBlockExt(assignmentAddData);
                          $.each(response.errors, function (key, value) {
                              toastr.error(value)
                          });
                      }
                  },
                  error: function (xhr, resp, text) {
                      console.log(xhr, resp, text);
                      toastr.error(text);
                      unBlockExt(assignmentAddData);
                  }
              });


          });

    });

</script>
@endpush
@endsection
