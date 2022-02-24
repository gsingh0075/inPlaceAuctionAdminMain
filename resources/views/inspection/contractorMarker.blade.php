<div class="container">
    <div class="row">
        @if(isset($data['contractor']) && !empty($data['contractor']))
        <div class="col-12">
             <h6 style="color: #000; margin-bottom: 0">{{ $data['contractor']['first_name'] }} {{ $data['contractor']['last_name'] }} ( {{ $data['contractor']['phone'] }} )</h6>
             <a href="{{ route('editContractor', $data['contractor']['contractor_id']) }}">View Contractor</a>
             <p>{{ $data['contractor']['company'] }}</p>
             @php $distanceCalculator = new \App\Helpers\GeocodeHelper() @endphp
             @if(!empty($data['lat']) && !empty($data['lng']))
                <p> <b>Distance</b> : {{ round($distanceCalculator->distance( $data['lat'], $data['lng'], $data['contractor']['lat'], $data['contractor']['lng'], 'M',2) ) }} miles away</p>
             @else
                <p> <b>Distance</b> : cannot be calculated</p>
             @endif
            <p class="text-danger text-bold-700"> ***** CONTRACTOR TYPE ****** </p>
                 @if($data['contractor']['is_equipment_contractor'] == 1)
                     <span class="btn btn-primary mr-1 mb-1">Equipment</span>
                 @endif
                 @if($data['contractor']['is_appraisal_contractor'] == 1)
                     <span class="btn btn-success mr-1 mb-1"> Appraisal</span>
                 @endif
                 @if($data['contractor']['is_inspection_contractor'] == 1)
                     <span class="btn btn-warning mr-1 mb-1">Inspection</span>
                 @endif
            <p class="text-danger text-bold-700">*** CONTRACTOR TYPE ***</p>
                @if(!empty($data['contractor']['notes'] ))
                    <p class="text-info"><b> !!! Important NOTES:</b>{{ $data['contractor']['notes'] }} !!!</p>
                @endif
             @if(!empty($data['contractor']->contractorCategories))
                 <p class="text-danger text-bold-700">*** INTERESTED IN ***</p>
                    @foreach($data['contractor']->contractorCategories as $c)
                        @if(!empty($c->category))
                            <span class="btn btn-primary mr-1 mb-1">{{ $c->category['category_name'] }}</span>
                        @endif
                    @endforeach
                <p class="text-danger text-bold-700">*** INTERESTED IN ***</p>
            @endif
        </div>
        @endif
    </div>
</div>
