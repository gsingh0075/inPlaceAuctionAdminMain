@extends('layouts.masterFront')
@section('content')
<div class="content-body container">
    <div class="col-12 addContainer mt-1" id="assignment-add-container">
        <div class="card-content">
            <div class="card-header text-center" style="background: #fff;">
                <img src="{{ asset('app-assets/images/logo/logo_big.jpg') }}" class="img-fluid" style="height: 6rem;" alt="InPlace Auction">
            </div>
            <div class="card-body">
                <p> Assignment was already created or exits. If you have any questions or need further clarification, please be sure to contact us at InPlaceAuction. </p>
                <p>Than You!</p>
                <p>Sincerely</p>
                <p><b>Edward Castagna</b></p>
                <p><b>Senior Appraiser, InPlaceAuction LLC.</b></p>
            </div>
        </div>
    </div>
</div>
@push('page-vendor-js')
@endpush
@push('page-js')
@endpush
@endsection