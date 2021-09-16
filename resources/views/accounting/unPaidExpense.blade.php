@if(isset($clientRemittance) && !empty($clientRemittance))
    @if(isset($clientRemittance->items) && !empty($clientRemittance->items))
        @foreach($clientRemittance->items as $i)
            @if(isset($i->item->expense) && !empty($i->item->expense))
                @foreach($i->item->expense as $e)
                    @if(isset($e->expenseAuth) && !empty($e->expenseAuth))
                        @if(isset($e->expenseAuth->invoice) && !empty($e->expenseAuth->invoice))
                            @if($e->expenseAuth->invoice->paid == null)
                                <div class="col-md-4 col-12">
                                   {{ $e->expenseAuth->expense_type }}:
                                </div>
                                <div class="col-md-8 col-12">
                                    <span class="text-danger">${{ round($e->expenseAuth->expense_amount,2) }}</span>
                                </div>
                             @endif
                        @endif
                    @endif
                @endforeach
            @endif
        @endforeach
    @endif
@endif