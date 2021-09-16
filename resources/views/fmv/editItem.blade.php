<form action="{{ route('updateFmvAjax') }}" method="post" id="updateItemFmv">
    <div class="modal-body" id="itemEditModalBody">
    <input type="hidden" name="fmv_item_id" value="{{ $item->fmv_item_id }}">
        <div class="form-row">
            <div class="col-md-4 col-12">
                <label for="make">Make</label>
            </div>
            <div class="col-md-8 form-group col-12">
                <input type="text" value="{{ $item->make }}" class="form-control" id="make" name="make">
                <p class="mb-0"><small class="text-muted text-info">Please type in slow for quick suggestions.</small></p>
            </div>
            <div class="col-md-4 col-12">
                <label for="model">Modal</label>
            </div>
            <div class="col-md-8 form-group col-12">
                <input type="text" class="form-control" value="{{ $item->model }}" id="model" name="model">
            </div>
            <div class="col-md-4 col-12">
                <label for="equip_year">Equipment Year</label>
            </div>
            <div class="col-md-8 form-group col-12">
                <input type="text" class="form-control" value="{{ $item->equip_year }}" id="equip_year" name="equip_year">
            </div>
            <div class="col-md-4 col-12">
                <label for="ser_nmbr">Serial</label>
            </div>
            <div class="col-md-8 form-group col-12">
                <input type="text" class="form-control" value="{{ $item->ser_nmbr }}" id="ser_nmbr" name="ser_nmbr">
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-4 col-12">
                <label for="orig_amt">Original cost</label>
            </div>
            <div class="col-md-8 form-group required col-12">
                <input type="text" class="form-control" value="{{ round($item->orig_amt,2) }}" id="orig_amt" name="orig_amt">
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-4 col-12">
                <label for="low_fmv_estimate">FLV</label>
            </div>
            <div class="col-md-8 form-group required col-12">
                <input type="text" class="form-control" value="{{ round($item->low_fmv_estimate,2) }}" id="low_fmv_estimate" name="low_fmv_estimate">
            </div>
            <div class="col-md-4 col-12">
                <label for="mid_fmv_estimate">OLV</label>
            </div>
            <div class="col-md-8 form-group required col-12">
                <input type="text" class="form-control" value="{{ round($item->mid_fmv_estimate,2) }}" id="mid_fmv_estimate" name="mid_fmv_estimate">
            </div>
            <div class="col-md-4 col-12">
                <label for="high_fmv_estimate">FMV</label>
            </div>
            <div class="col-md-8 form-group required col-12">
                <input type="text" class="form-control" value="{{ round($item->high_fmv_estimate,2) }}" id="high_fmv_estimate" name="high_fmv_estimate">
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-4 col-12">
                <label for="cost_of_recovery_low">Recovery LOW</label>
            </div>
            <div class="col-md-8 form-group required col-12">
                <input type="text" class="form-control" value="{{ round($item->cost_of_recovery_low,2) }}" id="cost_of_recovery_low" name="cost_of_recovery_low">
            </div>
            <div class="col-md-4 col-12">
                <label for="cost_of_recovery_high">Recovery HIGH</label>
            </div>
            <div class="col-md-8 form-group required col-12">
                <input type="text" class="form-control" value="{{ round($item->cost_of_recovery_high,2) }}" id="cost_of_recovery_high" name="cost_of_recovery_high">
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-4 col-12">
                <label for="item_description">Equipment Description</label>
            </div>
            <div class="col-md-8 form-group col-12">
                <textarea name="item_description" class="form-control" id="item_description">{{ $item->item_description }}</textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-4 col-12">
                <label for="autocompleteAddress">Equip Address</label>
            </div>
            <div class="col-md-8 form-group col-12">
                <input type="text" class="form-control" id="autocompleteAddressEdit" value="{{ $item->equip_address }}" name="equip_address">
                <p class="mb-0"><small class="text-muted text-info">Please type in slow for quick suggestions.</small></p>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-4 col-12">
                <label for="locality">City</label>
            </div>
            <div class="col-md-8 form-group col-12">
                <input type="text" class="form-control" value="{{ $item->equip_city }}" id="locality" name="equip_city">
            </div>
            <div class="col-md-4 col-12">
                <label for="administrative_area_level_1">State</label>
            </div>
            <div class="col-md-8 form-group col-12">
                <input type="text" class="form-control" value="{{ $item->equip_state }}" id="administrative_area_level_1" name="equip_state">
            </div>
            <div class="col-md-4 col-12">
                <label for="postal_code">Zip</label>
            </div>
            <div class="col-md-8 form-group col-12">
                <input type="text" class="form-control" value="{{ $item->equip_zip }}" id="postal_code" name="equip_zip">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Close</span>
        </button>
        <button type="button" id="updateItemSubmitBtn" class="btn btn-primary ml-1">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Update</span>
        </button>
    </div>
</form>
<script type="text/javascript">

    var updateItemSubmitBtn = $('#updateItemSubmitBtn');
    var updateItemFmv  = $('#updateItemFmv');


    $(document).ready(function(){

        initAutocomplete('autocompleteAddressEdit');
        // Auto Complete Request
        $('#make').autocomplete({
            source: function( request, response ) {
                $.ajax( {
                    url: "{{ route('itemSuggestion') }}",
                    dataType: "json",
                    type: "POST",
                    headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                    data: {
                        term: request.term
                    },
                    success: function( data ) {
                        //console.log(data);
                        response( data.data );
                    }
                } );
            },
            minLength: 2,
            select: function( event, ui ) {
                console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
                // Lets substitute the values.
                $('#model').val(ui.item.model);
                $('#low_fmv_estimate').val(ui.item.flv);
                $('#mid_fmv_estimate').val(ui.item.olv);
                $('#high_fmv_estimate').val(ui.item.fmv);
                $('#orig_amt').val(ui.item.orig);
                $('#cost_of_recovery_low').val(ui.item.recovery_low);
                $('#cost_of_recovery_high').val(ui.item.recovery_high);
            }
        });


        updateItemSubmitBtn.click(function(){
        blockExt(itemEditFormModal, $('#waitingMessage'));

            $.ajax({
                url: "{{route('updateFmvItemAjax')}}",
                type: "POST",
                dataType: "json",
                data: updateItemFmv.serialize(),
                headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                success: function (response) {
                    if (response.status) {

                        itemEditFormModal.modal('toggle');
                        itemModalOuterContainer.html('');
                        unBlockExt( itemEditFormModal );
                        Swal.fire({
                            title: "Good job!",
                            text: "Item updated",
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
                            toastr.error(value);
                        });
                        unBlockExt( itemEditFormModal );
                    }
                },
                error: function (xhr, resp, text) {

                    console.log(xhr, resp, text);
                    toastr.error(text);
                    unBlockExt( itemEditFormModal );
                }
            });

        });


    });
</script>