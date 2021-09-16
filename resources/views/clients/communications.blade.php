@extends('layouts.masterHorizontalChat')

@section('title','List Clients - InPlace Auction')

@push('page-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/app-chat.css') }}">
@endpush
@section('content')

        <div class="content-body">
          <!-- Chat Body -->

            <div class="app-content content">
                <div class="content-area-wrapper">
                    <div class="sidebar-left">
                        <div class="sidebar">
                            <!-- app chat sidebar start -->
                            <div class="chat-sidebar card">
                                <span class="chat-sidebar-close">
                                    <i class="bx bx-x"></i>
                                </span>
                                <div class="chat-sidebar-search">
                                    <div class="d-flex align-items-center">
                                        <!-- Search Bar -->
                                        <fieldset class="form-group position-relative has-icon-left mx-75 mb-0" data-children-count="1">
                                            <input type="text" class="form-control round" id="chat-search" placeholder="Search">
                                            <div class="form-control-position">
                                                <i class="bx bx-search-alt text-dark"></i>
                                            </div>
                                        </fieldset>
                                        <!-- End Search Bar -->
                                    </div>
                                </div>
                                <div class="chat-sidebar-list-wrapper pt-2 ps ps--active-y">
                                    <h6 class="px-2 pb-25 mb-0 text-uppercase">Clients</h6>
                                    <ul class="chat-sidebar-list">
                                        @if(isset($clientCommunications) && !empty($clientCommunications))
                                            @foreach($clientCommunications as $communication)
                                                <li data-id="{{ $communication->CLIENT_ID }}">
                                                    <div class="d-flex align-items-center">
                                                        <div class="chat-sidebar-name">
                                                            <h6 class="mb-0">{{ $communication->FIRSTNAME }}{{ $communication->LASTANME }}</h6><span class="text-muted">{{ $communication->COMPANY }}</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif


                                    </ul>
                               </div>
                            </div>
                            <!-- app chat sidebar ends -->
                        </div>
                    </div>

                    <!-- Right Section of communication -->

                    <div class="content-right">
                        <div class="content-overlay"></div>
                        <div class="content-wrapper">
                            <div class="content-header row">
                            </div>
                            <div class="content-body">
                                <!-- app chat overlay -->
                                <div class="chat-overlay"></div>
                                <!-- app chat window start -->
                                <section class="chat-window-wrapper">
                                    <div class="chat-start">
                                        <span class="bx bx-message chat-sidebar-toggle chat-start-icon font-large-3 p-3 mb-1"></span>
                                        <h4 class="d-none d-lg-block py-50 text-bold-500">Select a contact to start a chat!</h4>
                                        <button class="btn btn-light-primary chat-start-text chat-sidebar-toggle d-block d-lg-none py-50 px-1">Start
                                            Conversation!</button>
                                    </div>
                                    <div class="chat-area d-none">

                                        <div class="chat-header">
                                            <header class="d-flex justify-content-between align-items-center border-bottom px-1 py-75">
                                                <div class="d-flex align-items-center">
                                                    <div class="chat-sidebar-toggle d-block d-lg-none mr-1"><i class="bx bx-menu font-large-1 cursor-pointer"></i>
                                                    </div>
                                                    <h6 class="mb-0" id="currentClientHeader">
                                                        <!-- Loads Via Ajax -->
                                                    </h6>
                                                </div>
                                            </header>
                                        </div>
                                        <!-- chat card start -->
                                        <div class="card chat-wrapper shadow-none" id="main-communication-area">
                                            <div class="card-content">
                                                <div class="card-body chat-container" id="conversation-container">
                                                  <!-- Load Via Ajax -->
                                                </div>
                                            </div>
                                            <!-- Send New Chat Message -->
                                            <div class="card-footer chat-footer border-top px-2 pt-1 pb-0 mb-1">
                                                <form class="d-flex align-items-center" onsubmit="chatMessagesSend();" action="javascript:void(0);">
                                                    <i class="bx bx-face cursor-pointer"></i>
                                                    <i class="bx bx-paperclip ml-1 cursor-pointer"></i>
                                                    <input type="hidden" name="to_client_id" value="" id="to_client_id">
                                                    <input type="text" name="message_note" id="message-note" class="form-control chat-message-send mx-1" placeholder="Type your message here...">
                                                    <button type="submit" class="btn btn-primary glow send d-lg-flex">
                                                        <i class="bx bx-paper-plane"></i>
                                                        <span class="d-none d-lg-block ml-1">Send</span>
                                                    </button>
                                                </form>
                                            </div>
                                            <!-- End Send Chat Message -->
                                        </div>
                                        <!-- chat card ends -->

                                    </div>
                                </section>
                                <!-- app chat window ends -->
                            </div>
                        </div>
                    </div>
                    <!-- End Right Side -->

                </div>
            </div>


          <!-- End Chat Body -->
        </div>
    </div>
@push('page-vendor-js')
@endpush
@push('page-js')
    <script type="text/javascript">

        var chatSidebarListWrapper = $(".chat-sidebar-list-wrapper"),
            chatOverlay = $(".chat-overlay"),
            chatContainer = $(".chat-container"),
            chatSidebarProfileToggle = $(".chat-sidebar-profile-toggle"),
            chatSidebarClose = $(".chat-sidebar-close"),
            chatUserProfile = $(".chat-user-profile"),
            chatProfileClose = $(".chat-profile-close"),
            chatSidebar = $(".chat-sidebar"),
            chatArea = $(".chat-area"),
            chatStart = $(".chat-start"),
            chatSidebarToggle = $(".chat-sidebar-toggle"),
            chatMessageSend = $(".chat-message-send"),
            conversationContainer = $('#conversation-container'),
            currentClientHeader = $('#currentClientHeader');
            chatSideBarLi = $('.chat-sidebar-list-wrapper ul li');
            mainChatCommunicationArea = $('#main-communication-area');


        function blockCommunicationArea(){

            mainChatCommunicationArea.block({
                message: '<span class="semibold"> Loading...</span>',
                overlayCSS: {
                    backgroundColor: '#fff',
                    opacity: 0.8,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });
        }
        function unBlockCommunicationArea(){
            mainChatCommunicationArea.unblock();
        }


        $(document).ready(function () {
            "use strict";
            // menu user list perfect scrollbar initialization
            if (chatSidebarListWrapper.length > 0) {
                var menu_user_list = new PerfectScrollbar(".chat-sidebar-list-wrapper");
            }
            // user profile sidebar perfect scrollbar initialization
            if ($(".chat-user-profile-scroll").length > 0) {
                var profile_sidebar_scroll = new PerfectScrollbar(".chat-user-profile-scroll");
            }
            // chat area perfect scrollbar initialization
            if (chatContainer.length > 0) {
                var chat_user_user = new PerfectScrollbar(".chat-container");
            }
            if ($(".chat-profile-content").length > 0) {
                var chat_profile_content = new PerfectScrollbar(".chat-profile-content");
            }
            // user profile sidebar toggle
            chatSidebarProfileToggle.on("click", function () {
                chatUserProfile.addClass("show");
                chatOverlay.addClass("show");
            });

            // On chat menu sidebar close icon click
            chatSidebarClose.on("click", function () {
                chatSidebar.removeClass("show");
                chatOverlay.removeClass("show");
            });
            // on overlay click
            chatOverlay.on("click", function () {
                chatSidebar.removeClass("show");
                chatOverlay.removeClass("show");
                chatUserProfile.removeClass("show");
                chatProfile.removeClass("show");
            });
            // Add class active on click of Chat users list
            chatSideBarLi.on("click", function () {
                if (chatSideBarLi.hasClass("active")) {
                    chatSideBarLi.removeClass("active");
                }

                $(this).addClass("active");

                if (chatSideBarLi.hasClass("active")) {
                    chatStart.addClass("d-none");
                    chatArea.removeClass("d-none");

                    /* Send Ajax to load conversation */
                    let clientId = $(this).attr('data-id');
                    console.log(clientId);
                    blockCommunicationArea();
                    clientChatHistory(clientId);

                }
                else {
                    chatStart.removeClass("d-none");
                    chatArea.addClass("d-none");
                }
            });
            // app chat favorite star click
            $(".chat-icon-favorite i").on("click", function (e) {
                $(this).parent(".chat-icon-favorite").toggleClass("warning");
                $(this).toggleClass("bxs-star bx-star");
                e.stopPropagation();
            });
            // menu toggle till medium screen
            if ($(window).width() < 992) {
                chatSidebarToggle.on("click", function () {
                    chatSidebar.addClass("show");
                    chatOverlay.addClass("show");
                });
            }
            // auto scroll to bottom of Chat area
            $(".chat-sidebar-list li").on("click", function () {
                chatContainer.animate({ scrollTop: chatContainer[0].scrollHeight }, 400)
            });

            // click on main menu toggle will remove sidebars & overlays
            $(".menu-toggle").click(function () {
                chatSidebar.removeClass("show");
                chatOverlay.removeClass("show");
                chatUserProfile.removeClass("show");
                chatProfile.removeClass("show");
            });

            // chat search filter
            $("#chat-search").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                if (value !== "") {
                    $(".chat-sidebar-list-wrapper .chat-sidebar-list li").filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                }
                else {
                    // if search filter box is empty
                    $(".chat-sidebar-list-wrapper .chat-sidebar-list li").show();
                }
            });
            // window resize
            $(window).on("resize", function () {
                // remove show classes from overlay when resize, if size is > 992
                if ($(window).width() > 992) {
                    if (chatOverlay.hasClass("show")) {
                        chatOverlay.removeClass("show");
                    }
                }
                // menu toggle on resize till medium screen
                if ($(window).width() < 992) {
                    chatSidebarToggle.on("click", function () {
                        chatSidebar.addClass("show");
                        chatOverlay.addClass("show");
                    });
                }
                // disable on click overlay when resize from medium to large
                if ($(window).width() > 992) {
                    chatSidebarToggle.on("click", function () {
                        chatOverlay.removeClass("show");
                    });
                }
            });
        });

        // Get Client Chat

        function clientChatHistory( clientId) {

            $.ajax({
                url: "/getClientChat",
                type: "POST",
                dataType: "json",
                data: {
                    client_id:clientId,
                },
                headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                success: function (result) {
                    if (result.success) {
                        conversationContainer.html(result.html);
                        currentClientHeader.html(result.headerHtml);
                        $('#to_client_id').val(clientId);
                        unBlockCommunicationArea();
                    } else {
                        $.each(result.errors, function (key, value) {
                            toastr.error('Conversation loading failed '+value);
                        });
                        unBlockCommunicationArea();
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                    toastr.error(text);
                    unBlockCommunicationArea();
                }
            });

        }
        // Add message to chat
        function chatMessagesSend() {

            var message = chatMessageSend.val();
            var clientId = $('#to_client_id').val();

            if( clientId === "") {
                toastr.error('Please select the client to send message to');
                return false;
            }

            if ( message === "") {
                toastr.error('No message typed');
                return false;
            }

            if (message !== "" && clientId !== "") {

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to send message to client",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes!',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        blockCommunicationArea();
                        $.ajax({
                            url: "{{ route('saveCommunication') }}",
                            type: "POST",
                            dataType: "json",
                            data : {
                               'client_id' : clientId,
                               'note' :  message
                            },
                            headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                            success: function (response) {
                                if (response.status) {
                                    Swal.fire({
                                        title: "Sent!",
                                        text: "Message was sent!",
                                        type: "success",
                                        confirmButtonClass: 'btn btn-primary',
                                        buttonsStyling: false,
                                    }).then(function (result) {
                                        if (result.value) {
                                            clientChatHistory(clientId);
                                            chatMessageSend.val('');
                                        }
                                    });
                                } else {
                                    $.each(response.errors, function (key, value) {
                                        toastr.error(value)
                                    });
                                }
                            },
                            error: function (xhr, resp, text) {
                                console.log(xhr, resp, text);
                                toastr.error(text);
                                //unBlockFMVContainer();
                            }
                        });
                    }
                });

                chatContainer.scrollTop($(".chat-container > .chat-content").height());
            }
        }



    </script>
@endpush
@endsection
