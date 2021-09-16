<div class="container">
    <div class="row">
        @if(isset($data['assignment']) && !empty($data['assignment']))
        <div class="col-12">
             <h6 style="color: #000; margin-bottom: 0">{{ $data['assignment']['lease_nmbr'] }}</h6>
             <p>{{ $data['assignment']['ls_full_name'] }}</p>
             @if(!empty( $data['assignment']['ls_address1']))
                 <p> {{ $data['assignment']['ls_address1'] }}</p>
             @endif
        </div>
        <div class="col-12">
            @if(isset($data['assignment']) && !empty($data['assignment']))
                @if(isset($data['assignment']['items']) && !empty($data['assignment']['items']))
                    @foreach( $data['assignment']['items'] as $item)
                        <p style="margin-bottom: 10px;"> {{ $item['ITEM_MAKE'] }} {{ $item['ITEM_MODEL'] }} {{ $item['ITEM_YEAR'] }} <br>
                        @if(!empty($item->invoiceAuth))
                            @if(isset($item->invoiceAuth->invoice) && !empty($item->invoiceAuth->invoice))
                                @if($item->invoiceAuth->invoice->paid === 1)
                                   @if(isset($item->invoiceAuth->invoice->remittance) && !empty($item->invoiceAuth->invoice->remittance))
                                       @if($item->invoiceAuth->invoice->remittance->SENT === 1)
                                            <b class="text-info">CLIENT REMITTED</b>
                                       @endif
                                   @else
                                     <b class="text-info">CUSTOMER PAID</b>
                                   @endif
                                @endif
                            @else
                                <b class="text-info">SOLD</b>
                            @endif
                        @else
                           @if(!empty($item->itemContractor))
                              <b class="text-info">RECOVERY</b>
                           @else
                             <b class="text-info">NEW ASSIGNMENT</b>
                           @endif
                        @endif
                        </p>
                    @endforeach
                @endif
            @endif
        </div>
        <div class="col-12">
            <a href="{{ route('showAssignment', $data['assignment']['assignment_id']) }}" target="_blank" class="btn btn-primary mr-1 mb-1">View Assignment</a>
        </div>
        @endif
    </div>
</div>
