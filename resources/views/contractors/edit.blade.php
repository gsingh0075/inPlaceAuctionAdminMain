@extends('layouts.masterHorizontal')

@section('title','Edit Contractor - InPlace Auction')

@push('page-style')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/file-uploaders/dropzone.css') }}">
@endpush
@section('content')

    <div class="content-wrapper">
        <div class="content-header container">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Edit Contractor</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('getContractor') }}">View All Contractors</a>
                                </li>
                                <li class="breadcrumb-item active">Edit
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
                    <div class="col-12 addContainer" id="contractor-update-container">
                        <div class="card">
                           <!--<div class="card-header">
                            </div>-->
                            <div class="card-content">
                                <div class="card-body">
                                    <form class="form" id="contractorUpdateData" action="#" method="post">
                                        <div class="form-body">
                                            <!-- Details -->
                                            <div class="row mt-2">
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <input type="hidden" name="contractor_id" value="{{ $contractor->contractor_id }}">
                                                        <div class="col-md-4 col-12">
                                                            <label for="first_name">First Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="first_name" class="form-control" name="first_name" placeholder="First Name" value="{{ $contractor->first_name }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="last_name">Last Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="last_name" class="form-control" name="last_name" placeholder="Last Name" value="{{ $contractor->last_name }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="profile_type">Type</label>
                                                        </div>
                                                        @if(isset($contractor_type) && !empty($contractor_type))
                                                            @foreach($contractor_type as $key => $val)
                                                                <div class="col-md-8 form-group col-12 @if(!$loop->first) offset-md-4 @endif">
                                                                    <fieldset>
                                                                        <div class="checkbox">
                                                                            <input type="checkbox" class="form-control"  @if($contractor->$key === 1) checked @endif  value="1" name="{{ $key }}" id="{{ $key }}">
                                                                            <label for="{{ $key }}">{{ $val }}</label>
                                                                        </div>
                                                                    </fieldset>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="col-md-8 form-group col-12">
                                                                <!-- Empty No Profile set -->
                                                            </div>
                                                        @endif
                                                        <div class="col-md-4 col-12">
                                                            <label for="phone">Phone</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="phone" class="form-control" name="phone" placeholder="Phone" value="{{ $contractor->phone }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="cell">Cell</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="cell" class="form-control" name="cell" placeholder="Cell" value="{{ $contractor->cell }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="fax">Fax</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="fax" class="form-control" name="fax" placeholder="Fax" value="{{ $contractor->fax }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="email">Email's</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="email" class="form-control" name="email" placeholder="Email" value="{{ $contractor->email }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="company">Company Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="company" class="form-control" name="company" placeholder="Company" value="{{ $contractor->company }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="address">Street Address</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="address" class="form-control" name="address" placeholder="Address" value="{{ $contractor->address1 }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="city">City</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="city" class="form-control" name="city" placeholder="City" value="{{ $contractor->city }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="states">State</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <select id="states" name="states" class="form-control custom-select">
                                                                @foreach( $states as $s )
                                                                    <option value="{{ $s->STATE_ID }}" @if($contractor->state === $s->STATE_ID ) selected @endif>{{ $s->STATE }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="zip">Zip</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="zip" class="form-control" name="zip" placeholder="zip" value="{{ $contractor->zip1 }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="approved">Approved</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <select class="custom-select form-control" name="approved" id="approved">
                                                                <option value="1" @if($contractor->approved === 1 ) selected @endif>Yes</option>
                                                                <option value="0" @if($contractor->approved === 0 ) selected @endif>No</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="active">Active</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <select class="custom-select form-control" name="active" id="active">
                                                                <option value="1" @if($contractor->active === 1 ) selected @endif>Yes</option>
                                                                <option value="0" @if($contractor->active === 0 ) selected @endif>No</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="notes">Notes</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <textarea data-length="20" class="form-control char-textarea" name="notes" id="notes" rows="3" placeholder="Contractor notes">{{ $contractor->notes }}</textarea>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="profile_type">Profile</label>
                                                        </div>
                                                        @if(isset($profile) && !empty($profile))
                                                            @foreach($profile as $key => $val)
                                                                <div class="col-md-8 form-group col-12 @if(!$loop->first) offset-md-4 @endif">
                                                                    <fieldset>
                                                                        <div class="checkbox">
                                                                            <input type="checkbox" class="form-control" value="1" @if($contractor->$key === 1) checked @endif name="{{ $key }}" id="{{ $key }}">
                                                                            <label for="{{ $key }}">{{ $val }}</label>
                                                                        </div>
                                                                    </fieldset>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="col-md-8 form-group col-12">
                                                                <!-- Empty No Profile set -->
                                                            </div>
                                                        @endif
                                                        <div class="col-md-4 col-12">
                                                            <label for="coverage_territory">Territory Profile</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <textarea class="form-control char-textarea" name="coverage_territory" id="coverage_territory" rows="3" placeholder="Coverage Territory">{{ $contractor->coverage_territory }}</textarea>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="rating_criteria">Rating Criteria</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <select class="custom-select form-control" name="rating_criteria" id="rating_criteria">
                                                                <option value="">***Not Selected***</option>
                                                                <option value="Do Not Use" @if($contractor->rating_quality == "Do Not Use")  selected="selected" @endif>Do Not Use</option>
                                                                <option value="Good"  @if($contractor->rating_quality == "Good")  selected="selected" @endif>Good</option>
                                                                <option value="Better Than Most" @if($contractor->rating_quality == "Better Than Most") selected="selected" @endif>Better Than Most</option>
                                                                <option value="Best" @if($contractor->rating_qulaity == "Best")  selected="selected" @endif>Best</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="rating_comments">Rating Comments</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <textarea class="form-control char-textarea" name="rating_comments" id="rating_comments" rows="3" placeholder="Comments">{{ $contractor->rating_comments }}</textarea>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="area_interest">Areas of Interest</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <select class="custom-select form-control" name="area_interest[]" id="area_interest" multiple>
                                                                @php $selectedCategories = array(); @endphp
                                                                @if(!empty($contractor->contractorCategories))
                                                                    @foreach($contractor->contractorCategories as $ca)
                                                                        @php array_push($selectedCategories, $ca->Category_id); @endphp
                                                                    @endforeach
                                                                @endif
                                                                @php \Illuminate\Support\Facades\Log::info($selectedCategories); @endphp
                                                               @if(isset($categories) && !empty($categories))
                                                                   @foreach( $categories as $c)
                                                                       <option value="{{ $c->category_id }}" @if(in_array($c->category_id, $selectedCategories)) selected="selected" @endif>{{ $c->category_name }}</option>
                                                                   @endforeach
                                                               @endif
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="main_category">Main Category</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <select class="custom-select form-control" name="main_category" id="main_category">
                                                                @if(isset($categories) && !empty($categories))
                                                                       <option value="">Please Select</option>
                                                                    @foreach( $categories as $c)
                                                                        <option value="{{ $c->category_id }}" @if($contractor->main_category_id === $c->category_id) selected="selected" @endif>{{ $c->category_name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                              <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary mr-1 mb-1" id="updateContractorBtn">Update Contractor</button>
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

    var contractorUpdateData = $('#contractorUpdateData');

    $(document).ready(function(){

       $('#main_category').select2({
            placeholder: "Category"
        });

        $('#area_interest').select2({
            placeholder: "Category"
        });

        // Add Button Clicked.
         $('#updateContractorBtn').click(function(e){

            e.preventDefault();
            blockExt(contractorUpdateData, $('#waitingMessage'));

            $.ajax({
                url: "{{route('updateContractor')}}",
                  type: "POST",
                  dataType: "json",
                  data: contractorUpdateData.serialize(),
                  headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                  success: function (response) {
                      if (response.status) {
                          //console.log(response)
                          Swal.fire({
                              title: "Good job!",
                              text: "Contractor Successfully updated!",
                              type: "success",
                              confirmButtonClass: 'btn btn-primary',
                              buttonsStyling: false,
                          }).then(function (result) {
                              if (result.value) {
                                  window.location = "{{ route('getContractor') }}";
                              }
                              unBlockExt(contractorUpdateData);
                          });
                      } else {
                          unBlockExt(contractorUpdateData);
                          $.each(response.errors, function (key, value) {
                              toastr.error(value)
                          });
                      }
                  },
                  error: function (xhr, resp, text) {
                      console.log(xhr, resp, text);
                      toastr.error(text);
                      unBlockExt(contractorUpdateData);
                  }
              });


          });

    });

</script>
@endpush
@endsection
