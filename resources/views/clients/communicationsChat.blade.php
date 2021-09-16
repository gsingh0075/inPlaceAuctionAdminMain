<div class="chat-content">
    @if(isset($clientCommunication->communication) && !empty($clientCommunication->communication))
        @foreach( $clientCommunication->communication as $communication )
            <div class="chat @if( $communication->posted_by != 'ADMIN') chat-left @endif">
                <div class="chat-body">
                    <div class="chat-message">
                        <p>{{ $communication->communication_note }}</p>
                        <span class="chat-time">{{ \Carbon\Carbon::createFromTimestamp(strtotime($communication->dt_stmp))->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
