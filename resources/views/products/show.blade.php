@extends('layouts.appLTE')


@section('content')
    <style>
        .modal-body {
            height: 80vh;
            overflow-y: auto;
        }
    </style>
    <div class="content-wrapper">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Show</h3>
            </div>
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="float-end p-2 mr-5">
                        <a class="btn btn-primary" href="{{ route('products.index') }}"> Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <strong>Name:</strong>
                                    <input type="text" name="name" value="{{ $product->name }}" class="form-control"
                                        placeholder="Name" disabled>
                                </div>
                                <div class="form-group">
                                    <strong>Detail:</strong>
                                    <textarea class="form-control" style="height:150px" name="detail" placeholder="Detail"
                                        disabled>{{ $product->detail }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Files</h5>
                                @if ($files->count())
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="">Filename</th>
                                                <th scope="">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($files as $file)
                                                <tr>
                                                    <td>{{ $file->filename }}</td>
                                                    <td class="text-right">
                                                        <button type="button" data-path="{{ URL::asset($file->path) }}"
                                                            data-filename="{{ $file->filename }}" data-toggle="modal"
                                                            data-target="#filePreview"
                                                            class="btn btn-primary showModal btn-sm">
                                                            View
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="mt-3 text-center">
                                        <p><i class="bi bi-file-earmark-x"></i> No files uploaded.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="filePreview" tabindex="-1" aria-labelledby="filePreviewLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="filePreviewLabel">Filename</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe id="iframe_file" frameborder="0"></iframe>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
