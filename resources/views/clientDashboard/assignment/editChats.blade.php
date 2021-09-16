<!-- Communication Section -->
<section id="chats">
    <div class="row">
        <div class="col-12 widget-chat-card">
            <div class="widget-chat widget-chat-messages">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body widget-chat-container widget-chat-scroll" id="public-chat" style="background-color: #e5e9ed; border-radius: 0">
                            <div class="chat-content">
                                @if(isset($assignment->communicationsPublic) && (count($assignment->communicationsPublic)>0))
                                    @foreach($assignment->communicationsPublic as $communication)
                                        @if($communication->posted_by == 'ADMIN')
                                            <div class="chat chat-left">
                                                <div class="chat-body">
                                                    <div class="chat-message">
                                                        <p>{{ $communication->communication_note }}</p>
                                                        <span class="chat-time">posted by IPA on {{ \Carbon\Carbon::createFromTimestamp(strtotime($communication->dt_stmp))->format('d M Y') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="chat">
                                                <div class="chat-body">
                                                    <div class="chat-message">
                                                        <p>{{ $communication->communication_note }}</p>
                                                        <span class="chat-time">posted by you on {{ \Carbon\Carbon::createFromTimestamp(strtotime($communication->dt_stmp))->format('d M Y') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="badge badge-pill badge-light-secondary my-1">No conversation
                                        found
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer">
                            <textarea class="form-control char-textarea active" name="public_notes" id="public_notes" rows="3" placeholder="Send message to IPA Team" style="color: rgb(48, 65, 86);"></textarea>
                            <button type="button" class="btn btn-primary mt-1 saveCommunication" id="updatePublicNotes">Send Message</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Communication Section -->
