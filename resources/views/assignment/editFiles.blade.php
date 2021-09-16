<!-- Files List -->
<section id="assignment-files-containers">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Files</h4>
                    <span style="float: right;">
                        <button type="button" class="btn btn-primary mr-1 mb-1" id="addFiles"
                                data-toggle="modal" data-target="#itemAddFilesModal">Add Files</button>
                    </span>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table dataTable zero-configuration">
                                <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>Type</th>
                                    <th>Upload Date</th>
                                    <th>View</th>
                                    <th>Visibility</th>
                                    <th>Delete</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($assignment->files) > 0)
                                    @foreach( $assignment->files as $file )
                                        <tr>
                                            <td>{{ $file->logs }}</td>
                                            <td>{{ $file->fileType }} </td>
                                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $file->updated_at)->format('j F, Y') }}</td>
                                            <td>
                                                <a href="{{ $file->fileSignedUrl }}" target="_blank">View</a>
                                            </td>
                                            <td>
                                                @if($file->status === 1)
                                                    <a href="javascript:void(0)" data-url="{{ route('visibilityReportFiles') }}" class="visibilityReport" data-id="{{ $file->id }}" data-status="0">Hide on Client Portal</a>
                                                @else
                                                    <a href="javascript:void(0)" data-url="{{ route('visibilityReportFiles') }}" class="visibilityReport" data-id="{{ $file->id }}" data-status="1">Show on Client Portal</a>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" class="deleteAssFile" data-attr-link="{{ route('deleteAssignmentFmv', $file->id) }}">Delete</a>
                                            </td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">No Assigment Files</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Files list -->

<!-- Item Form -->
<div class="modal fade text-left" id="itemAddFilesModal" data-backdrop="static" data-keyboard="false"
     tabindex="-1" role="dialog" aria-labelledby="itemAddFilesModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">Add Files</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <form action="{{ route('addItemAjax') }}" method="post" enctype="multipart/form-data"
                  id="addFilesAssignment">
                <input type="hidden" name="assignment_id" id="assignment_id"
                       value="{{ $assignment->assignment_id }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="dropzone" id="assignmentFiles">
                                <div class="dz-message">Drop Files Here To Upload</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" id="addFileFormModal" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Add</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add Form Files End -->
