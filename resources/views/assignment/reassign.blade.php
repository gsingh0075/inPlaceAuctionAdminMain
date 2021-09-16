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
                        <h5 class="content-header-title float-left pr-1 mb-0">RE:Assignment</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('getAssignment') }}">List</a>
                                </li>
                                <li class="breadcrumb-item active">Re Assign Assignment
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
                                    <form class="form" id="reAssignAssignmentData" action="#" method="post">
                                        <div class="form-body">
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <h6 class="py-50">Client Details</h6>
                                                </div>
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label for="client_id">Choose Assignment</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <select name="assignment_id" id="assignment_id" class="custom-select form-control">
                                                                @if(isset($assignment) && !empty($assignment))
                                                                    @foreach( $assignment as $a)
                                                                        <option value="{{ $a->assignment_id }}">( {{ $a->assignment_id }}) @if(!empty($a->client)) Client#{{ $a->client->clientInfo->CLIENT_ID }} {{ $a->client->clientInfo->FIRSTNAME }} {{ $a->client->clientInfo->LASTNAME }} ({{ $a->client->clientInfo->COMPANY }}) @endif</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="client_id">Choose Client</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                          <select name="client_id" id="client_id" class="custom-select form-control">
                                                              @if(isset($clients) && !empty($clients))
                                                                  @foreach( $clients as $c)
                                                                      <option value="{{ $c->CLIENT_ID }}">{{ $c->FIRSTNAME }} {{ $c->LASTNAME }} ( {{ $c->COMPANY }} )</option>
                                                                  @endforeach
                                                              @endif
                                                          </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                              <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary mr-1 mb-1" id="updateReAssignAssignment">Update Assignment</button>
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

    var assignmentAddData = $('#reAssignAssignmentData');

    $(document).ready(function(){

        // Add Button Clicked.
          $('#updateReAssignAssignment').click(function(e){

              e.preventDefault();
              blockExt(assignmentAddData, $('#waitingMessage'));

              $.ajax({
                  url: "{{route('ajaxReassignAssignment')}}",
                  type: "POST",
                  dataType: "json",
                  data: assignmentAddData.serialize(),
                  headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                  success: function (response) {
                      if (response.status) {
                          //console.log(response)
                          Swal.fire({
                              title: "Good job!",
                              text: "Assignment updated!",
                              type: "success",
                              confirmButtonClass: 'btn btn-primary',
                              buttonsStyling: false,
                          }).then(function (result) {
                              if (result.value) {
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
