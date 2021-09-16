@extends('clientDashboard.layouts.masterHorizontal')
@section('title','View Assignment - InPlace Auction')
@push('page-style')
@endpush
@section('content')
    <div class="content-wrapper">
        <div class="content-header container">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">IPA # {{ $assignment->assignment_id }}</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('getAssignmentClient') }}">List</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body container">

            <!-- Lets make the Tabs here -->
            <div class="col-12 p-0">
                <ul class="nav nav-pills my-1 mx-1" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="v-pills-lease-tab" data-toggle="pill" href="#v-pills-lease" role="tab" aria-controls="v-lease-home" aria-selected="true">Assignment Details</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a  class="nav-link" id="v-pills-files-tab" data-toggle="pill" href="#v-pills-files" role="tab" aria-controls="v-pills-files" aria-selected="false">Files</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="v-pills-chat-tab" data-toggle="pill" href="#v-pills-chat" role="tab" aria-controls="v-pills-chat" aria-selected="false">Conversations</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="v-pills-items-tab" data-toggle="pill" href="#v-pills-items" role="tab" aria-controls="v-pills-items" aria-selected="false">Items</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="v-pills-invoices-tab" data-toggle="pill" href="#v-pills-invoices" role="tab" aria-controls="v-pills-invoices" aria-selected="false">Invoices</a>
                    </li>
                </ul>
            </div>
            <!-- Lets End the Tabs here -->

            <section id="floating-label-layouts">
                <div class="row match-height">
                    <div class="col-12 addContainer" id="fmv-update-container">
                        <div class="card">
                                <input type="hidden" id="assignment_id" value="{{ $assignment->assignment_id }}">
                            <!-- Lets Start the Tab Layout -->
                            <div class="tab-content" id="v-pills-tabContent">
                                <div class="tab-pane fade show active" id="v-pills-lease" role="tabpanel" aria-labelledby="v-pills-lease-tab">
                                    @include('clientDashboard.assignment.editLeaseDetails')
                                </div>
                                <div class="tab-pane fade" id="v-pills-files" role="tabpanel" aria-labelledby="v-pills-files-tab">
                                    @include('clientDashboard.assignment.editFiles')
                                </div>
                                <div class="tab-pane fade" id="v-pills-chat" role="tabpanel" aria-labelledby="v-pills-chat-tab">
                                    @include('clientDashboard.assignment.editChats')
                                </div>
                                <div class="tab-pane fade" id="v-pills-items" role="tabpanel" aria-labelledby="v-pills-items-tab">
                                    @include('clientDashboard.assignment.editItems')
                                </div>
                                <div class="tab-pane fade" id="v-pills-invoices" role="tabpanel" aria-labelledby="v-pills-invoices-tab">
                                    @include('clientDashboard.assignment.editClientInvoice')
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
    <script type="text/javascript">

        /****** Save Notes **/
        $('.saveCommunication').click(function () {

            blockExt($('#chats'), $('#waitingMessage'));

            $.ajax({
                url: "{{ route('saveClientCommunicationAssignment') }}",
                type: "POST",
                dataType: "json",
                data: {
                    'assignment_id': $('#assignment_id').val(),
                    'note': $('#public_notes').val()
                },
                headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                success: function (response) {
                    if (response.status) {
                        Swal.fire({
                            title: "Good job!",
                            text: "Note added successfully!",
                            type: "success",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        }).then(function (result) {
                            if (result.value) {
                                window.location.reload();
                            }
                        });
                    } else {
                        $.each(response.errors, function (key, value) {
                            toastr.error(value)
                        });
                        unBlockExt($('#chats'));
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                    toastr.error(text);
                    unBlockExt($('#chats'));
                }
            });


        });


        if ($(".widget-chat-scroll").length > 0) {
            var widget_chat_scroll = new PerfectScrollbar(".widget-chat-scroll", { wheelPropagation: false });
        }
    </script>
@endpush
@endsection
