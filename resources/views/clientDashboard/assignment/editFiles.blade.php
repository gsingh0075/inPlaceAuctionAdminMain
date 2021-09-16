<!-- Files List -->
<section id="assignment-files-containers">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table dataTable zero-configuration">
                                <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>Type</th>
                                    <th>Added Date</th>
                                    <th>View</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($assignment->clientFiles) > 0)
                                    @foreach( $assignment->clientFiles as $file )
                                        <tr>
                                            <td>{{ $file->logs }}</td>
                                            <td>{{ $file->fileType }} </td>
                                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $file->updated_at)->format('j F, Y') }}</td>
                                            <td>
                                                <a href="{{ $file->fileSignedUrl }}" target="_blank">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">No Files Found</td>
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
