@extends('clientDashboard.layouts.masterHorizontal')

@section('title','Home Dashboard')

@push('page-style')
   <style>
       .table a {
           text-decoration: underline;
       }
   </style>
@endpush

@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- Dashboard Starts -->
            <section id="dashboard-ecommerce">
                <div class="row">
                    <div class="col-md-6 col-12">
                    <div class="col-12">
                       <div class="row">
                    <!-- Dashboard Analytics Number -->
                    <div class="col-md-4 col-12 dashboard-users-danger">
                        <div class="card text-center">
                            <div class="card-content">
                                <div class="card-body py-1">
                                    <div class="badge-circle badge-circle-lg badge-circle-light-secondary mx-auto mb-50">
                                        <i class="bx bx-note font-medium-5"></i>
                                    </div>
                                    <div class="text-muted line-ellipsis">Total FMV</div>
                                    <h3 class="mb-0">{{ $totalFmv }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12 dashboard-users-danger">
                        <div class="card text-center">
                            <div class="card-content">
                                <div class="card-body py-1">
                                    <div class="badge-circle badge-circle-lg badge-circle-light-secondary mx-auto mb-50">
                                        <i class="bx bx-note font-medium-5"></i>
                                    </div>
                                    <div class="text-muted line-ellipsis">Open Assignments</div>
                                    <h3 class="mb-0">{{ $totalOpenAssignment }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12 dashboard-users-danger">
                        <div class="card text-center">
                            <div class="card-content">
                                <div class="card-body py-1">
                                    <div class="badge-circle badge-circle-lg badge-circle-light-secondary mx-auto mb-50">
                                        <i class="bx bx-note font-medium-5"></i>
                                    </div>
                                    <div class="text-muted line-ellipsis">Close Assignments</div>
                                    <h3 class="mb-0">{{ $totalCloseAssignment }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Analytics Number -->
                    <!-- chat Widget Starts -->
                    <div class="col-12 widget-chat-card" id="chatHomePage">
                        <div class="widget-chat widget-chat-messages">
                            <div class="card">
                                <div class="card-header border-bottom p-0">
                                    <div class="media m-75">
                                        <a class="media-left" href="JavaScript:void(0);">
                                            <div class="avatar mr-75">
                                            </div>
                                        </a>
                                        <div class="media-body">
                                            <p>Below is our general communications tool. Please use this tool as a discussion tool between IPA and your company. We have further communications tools within the assignment areas, used for specific accounts.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body widget-chat-container widget-chat-scroll">
                                    <div class="chat-content">
                                        @if(isset($communication) && !empty($communication))
                                            @foreach($communication as $comm)
                                                @if($comm->posted_by == 'ADMIN')
                                                    <div class="chat chat-left">
                                                        <div class="chat-body">
                                                            <div class="chat-message">
                                                                <p>{{ $comm->communication_note }}</p>
                                                                <span class="chat-time">{{ \Carbon\Carbon::createFromTimestamp(strtotime($comm->dt_stmp))->format('d M Y') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="chat">
                                                        <div class="chat-body">
                                                            <div class="chat-message">
                                                                <p>{{ $comm->communication_note }}</p>
                                                                <span class="chat-time">{{ \Carbon\Carbon::createFromTimestamp(strtotime($comm->dt_stmp))->format('d M Y') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            <div class="badge badge-pill badge-light-secondary my-1">No conversation found</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer border-top p-1">
                                    <form class="d-flex align-items-center" action="javascript:void(0);">
                                        <input type="text" class="form-control widget-chat-message mx-75" id="homePage-message" name="message" placeholder="Type here...">
                                        <button type="button" id="sendMessage" class="btn btn-primary glow"><i class="bx bx-paper-plane"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- chat Widget Ends -->
                       </div> <!-- Row -->
                    </div>
                    </div>

                    <div class="col-md-6 col-12">
                            <!-- Invoices Out -->
                            <div class="col-12 dashboard-marketing-campaign">
                                <div class="card marketing-campaigns">
                                    <div class="card-header d-flex justify-content-between align-items-center pb-0">
                                        <div class="container p-0">
                                            <div class="row">
                                                <div class="col-12 p-0">
                                                    <h4 class="card-title">Pending Invoices</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-content" >
                                        <div class="card-body p-0" id="customerInvoices">
                                            <!-- Loads Via Ajax -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!-- End Here -->
                    </div>
                </div>

            </section>
            <!-- Dashboard E-commerce ends -->

        </div>
    </div>

@push('page-js')
    <script type="text/javascript">
        if ($(".widget-chat-scroll").length > 0) {
            var widget_chat_scroll = new PerfectScrollbar(".widget-chat-scroll", { wheelPropagation: false });
        }

        var homePageMessage = $('#homePage-message');

        $(document).ready(function () {
            blockCustomerReceivables();
            loadCustomerReceivables();
        });
        /** Send Message System ***/
        $('#sendMessage').click(function(){

            var message = homePageMessage.val();

            if(message === ''){
                toastr.error('Empty message');
                return;
            }

            blockExt($('#chatHomePage'), $('#waitingMessage'));

            $.ajax({
                url: "{{ route('saveClientCommunication') }}",
                type: "POST",
                dataType: "json",
                data: {
                    'note': homePageMessage.val()
                },
                headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                success: function (response) {
                    if (response.status) {
                        Swal.fire({
                            title: "Good job!",
                            text: "Message sent successfully!",
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
                        unBlockExt($('#chatHomePage'));
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                    toastr.error(text);
                    unBlockExt($('#chatHomePage'));
                }
            });


        });

        function blockCustomerReceivables(){

            $('#customerInvoices').block({
                message: '<div class="bx bx-revision icon-spin font-medium-2"></div>',
                showOverlay: false,
                css: {
                    width: 50,
                    height: 50,
                    lineHeight: 1,
                    color: '#ffffff',
                    border: 0,
                    padding: 15,
                    backgroundColor: '#333'
                }
            });
        }

        function unBlockCustomerReceivables(){

            $('#customerInvoices').unblock();
        }


        /***** Load Home Page with Invoices ***/
        function loadCustomerReceivables(){
             console.log('came here');
            $.ajax({
                url: '/getClientInvoices',
                type: "GET",
                dataType: "json",
                headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                success: function (result) {
                    if (result.status) {
                        // Update the Div
                        $('#customerInvoices').html(result.html);
                        unBlockCustomerReceivables();
                    } else {
                        $.each(result.errors, function (key, value) {
                            //toastr.error(value);
                            console.log(value);
                        });
                        unBlockCustomerReceivables();
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                    //toastr.error(text);
                }
            });

        }
    </script>
@endpush
@endsection
