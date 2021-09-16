<div class="container">
    <div class="row">
        @if(isset($data['contractor']) && !empty($data['contractor']))
        <div class="col-12">
             <h6 style="color: #000; margin-bottom: 0">{{ $data['contractor']['first_name'] }} {{ $data['contractor']['last_name'] }} ( {{ $data['contractor']['phone'] }} )</h6>
             <a href="{{ route('editContractor', $data['contractor']['contractor_id']) }}">View Contractor</a>
             <p>{{ $data['contractor']['company'] }}</p>
                @if(!empty($data['contractor']['notes'] ))
                    <p class="text-info"><b> !!! Important NOTES:</b>{{ $data['contractor']['notes'] }} !!!</p>
                @endif
             @if(!empty($data['contractor']->contractorCategories))
                 <p class="text-danger">*** INTERESTED IN ***</p>
                    @foreach($data['contractor']->contractorCategories as $c)
                        @if(!empty($c->category))
                            <span class="btn btn-primary mr-1 mb-1">{{ $c->category['category_name'] }}</span>
                        @endif
                    @endforeach
                <p class="text-danger">*** INTERESTED IN ***</p>
            @endif
        </div>
        <div class="col-12">
            <p class="text-info">ITEMS</p>
            @if(isset($data['assignment']) && !empty($data['assignment']))
                @if(isset($data['assignment']['items']) && !empty($data['assignment']['items']))
                    @foreach( $data['assignment']['items'] as $item)
                        <p style="margin-bottom: 0"> {{ $item['ITEM_MAKE'] }} {{ $item['ITEM_MODEL'] }} {{ $item['ITEM_YEAR'] }} <b> {{ $item['ITEM_SERIAL'] }} </b></p>
                        @php $distanceCalculator = new \App\Helpers\GeocodeHelper() @endphp
                        @if(!empty($item['lat']) && !empty($item['lng']))
                            <p> <b>Distance</b> : {{ round($distanceCalculator->distance( $item['lat'], $item['lng'], $data['contractor']['lat'], $data['contractor']['lng'], 'M',2) ) }} miles away</p>
                        @else
                            <p> <b>Distance</b> : cannot be calculated</p>
                        @endif
                    @endforeach
                @endif
            @endif
        </div>
        <div class="col-12">
            <button type="button" data-id="{{ $data['contractor']['contractor_id'] }}" data-email="{{ $data['contractor']['email'] }}" class="btn btn-primary mr-1 mb-1" id="authorize" data-toggle="modal" data-target="#authorizeModal">Authorize Contractor</button>
        </div>
        @endif
    </div>
</div>
