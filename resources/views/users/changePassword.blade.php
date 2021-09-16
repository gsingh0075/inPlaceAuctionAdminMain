@extends('layouts.masterHorizontal')

@section('title','Update Password - InPlace Auction')

@push('page-style')
@endpush
@section('content')

    <div class="content-wrapper">
        <div class="content-header container">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Update Password</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
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
                                    <form class="form" id="userChangePassword" action="#" method="post">
                                        <div class="form-body">
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <h6 class="py-50">Password Details</h6>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label for="current_password">Current Password</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="password" id="current_password" class="form-control" name="current_password" placeholder="Current Password" value="">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="new_password">New Password</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="password" id="new_password" class="form-control" name="new_password" placeholder="New Password" value="">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="confirm_password">Confirm Password</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="password" id="confirm_password" class="form-control" name="confirm_password" placeholder="Confirm Password" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                              <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary mr-1 mb-1" id="updatePasswordBtn">Update Password</button>
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
        </div>
    </div>
@push('page-vendor-js')
@endpush
@push('page-js')
<script>

    var userChangePasswordForm = $('#userChangePassword');

    $(document).ready(function(){

        // Update Password Click Function
          $('#updatePasswordBtn').click(function(e){

              e.preventDefault();
              blockExt(userChangePasswordForm, $('#waitingMessage'));

              $.ajax({
                  url: "{{route('userUpdatePassword')}}",
                  type: "POST",
                  dataType: "json",
                  data: userChangePasswordForm.serialize(),
                  headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                  success: function (response) {
                      if (response.status) {
                          //console.log(response)
                          Swal.fire({
                              title: "Good job!",
                              text: "Password updated!",
                              type: "success",
                              confirmButtonClass: 'btn btn-primary',
                              buttonsStyling: false,
                          }).then(function (result) {
                              if (result.value) {
                                  console.log('User Password was successfully updated');
                                  window.location.reload();
                              }
                              unBlockExt(userChangePasswordForm);
                          });
                      } else {
                          unBlockExt(userChangePasswordForm);
                          $.each(response.errors, function (key, value) {
                              toastr.error(value)
                          });
                      }
                  },
                  error: function (xhr, resp, text) {
                      console.log(xhr, resp, text);
                      toastr.error(text);
                      unBlockExt(userChangePasswordForm);
                  }
              });


          });

    });

</script>
@endpush
@endsection
