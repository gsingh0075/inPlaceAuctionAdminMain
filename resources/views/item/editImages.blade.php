<div class="card-body">
    <div class="col-12 text-right p-0">
        @if( (isset($itemImages)) && (count($itemImages)> 0) )
            <button type="button"
                    class="btn btn-primary mr-1 mb-1"
                    id="generatePictureReportBtn"
                    data-toggle="modal"
                    data-target="#generatePictureReport">Generate Picture Report
            </button>
        @endif
    </div>
    <div class="col-12 p-0">
        <div class="row">
            @if(isset($itemImages) && !empty($itemImages))
                @foreach($itemImages as $image)
                    @if(isset($image->imageSignedUrl) && !empty($image->imageSignedUrl))
                        <div class="col-md-4 col-12">
                            <img src="{{ $image->imageSignedUrl }}" alt="item-image" class="img-fluid p-1">
                            <a href="javascript:void(0)" data-attr-link="{{ route('deleteItemPicture', $image->image_id) }}" class="deleteItemImage p-1">Delete</a>
                        </div>
                @endif
            @endforeach
        @endif
        </div>
    </div>
</div>

<div id="waitingMessage" class="col-12 container" style="display: none">
    <p>Loading......</p>
</div>

<!-- Generate Image Condition Report -->
<div class="modal fade text-left" id="generatePictureReport" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="generatePictureReport" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Please select the images</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <form action="#" method="post" enctype="multipart/form-data" id="generatePictureReportForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6 mb-2">
                            <p>Effective Report Date</p>
                        </div>
                        <div class="col-6 mb-2">
                            <input type="text" id="item_report_effective_date" class="form-control effectiveDate" name="item_report_effective_date" placeholder="Report Date" value="">
                        </div>
                        <div class="col-6">
                            <p>Drop Item Image on the right in order for report</p>
                            @if(isset($itemImages) && !empty($itemImages))
                                <div class="row">
                                    @foreach($itemImages as $image)
                                        @if(isset($image->imageSignedUrl) && !empty($image->imageSignedUrl))
                                            <div class="col-md-4 col-12 mt-1">
                                                <div class="row">
                                                    <div class="col-11">
                                                        <img src="{{ $image->imageSignedUrl }}" data-image-id="{{ $image->image_id }}" data-image-url="{{ $image->imageSignedUrl }}" alt="item-image" class="img-fluid itemDraggable" style="width: 100px; height: 100px;">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="col-6" id="itemImageDropArea">
                            <div class="row" id="itemImageDropAreaContainer">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submitGeneratePictureReportBtn" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Generate Report</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Image Condition Reports -->
<script type="text/javascript">

    var itemDraggable = $('.itemDraggable');
    var itemImageDropArea = $('#itemImageDropArea');
    var itemImageDropAreaContainer = $('#itemImageDropAreaContainer');
    var itemReportEffectiveDate = $('#item_report_effective_date');
    var itemConditionReport = [];

    $('#item_report_effective_date').pickadate(); // Date Picker

    // Drag Drop Feature.
    itemDraggable.draggable({
        helper: "clone",
        appendTo: 'body',
        cursor: "move",
    });
    itemImageDropArea.droppable({
        drop: function(event, ui){
            var imageItem = ui.draggable;
            var imageId = imageItem.attr('data-image-id');
            var imageSrc = imageItem.attr('data-image-url');
            var itemIncrement = itemConditionReport.length++;
            console.log(imageId);
            console.log(imageSrc);
            itemConditionReport[itemIncrement] = parseInt(imageId);
            console.log(itemConditionReport);
            let imageHtml = '<div class="col-4 py-1" id="imageContainer_'+imageId+'"><img src="'+imageSrc+'" alt="draggedImage" class="img-fluid" style="width: 100px; height: 100px;"><p class="pb-0 pt-1"><a href="javascript:void(0)" class="removeImageReport" data-id="'+imageId+'">Remove<p></div>';
            itemImageDropAreaContainer.append(imageHtml);
        }
    });



    // Generate Picture Report
    $('#submitGeneratePictureReportBtn').click(function(){

        console.log('Generate Picture report');
        console.log(itemReportEffectiveDate.val());
        //let itemImages = [];
        //$('input[name="itemImageRpt[]"]:checked').each(function () {
        //itemImages.push($(this).val());
        //});
        //console.log(itemImages);
        //console.log('Ready to send in back end for review');

        console.log(itemConditionReport);
        if(itemConditionReport.length === 0 ){
            toastr.error('Please drag the images to add in report');
            return false;
        }
        blockExt($('.content-wrapper'), $('#waitingMessage'));
        $.ajax({
            url: "{{ route('generatePictureReport') }}",
            type: "POST",
            dataType: "json",
            data: {
                'item_report_effective_date': itemReportEffectiveDate.val(),
                'item_image_ids': itemConditionReport,
                'item_id': $('#item_id').val(),
            },
            headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
            success: function (response) {
                if (response.status) {
                    Swal.fire({
                        title: "Good job!",
                        text: "Picture report is generated successfully!",
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
                    unBlockExt($('.content-wrapper'));
                }
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
                toastr.error(text);
                //unBlockFMVContainer();
                unBlockExt($('.content-wrapper'));
            }
        });


    });



</script>

