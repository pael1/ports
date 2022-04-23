@extends('layouts.appLTE')


@section('content')
    <div class="content-wrapper">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Complaints</h3>
            </div>
            {{-- <div class="row margin-tb">
                <div class="col-lg-12 margin-tb">
                    
                    <div class="float-end p-2 mr-5">
                        @can('product-create')
                            <a class="btn btn-success btn-sm" href="{{ route('products.create') }}"> Create New Product</a>
                        @endcan
                    </div>
                </div>
            </div> --}}

            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="float-end p-2 mr-5">
                        @can('product-create')
                            <a class="btn btn-success btn-sm" href="{{ route('complaints.create') }}">Create New Complaint</a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body">
                {{-- @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                @endif --}}


                <table id="generalTable" class="table">
                    <thead>
                        <tr>
                            <th>Recieved By</th>
                            <th>Assigned To</th>
                            <th>Date Filed</th>
                            <th>Case</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            {{-- {!! $products->links() !!} --}}
        </div>
    </div>

    @push('scripts')
        <script>
            $(function() {
                var complaintTable = $("#generalTable").DataTable({
                    serverSide: true,
                    processing: true,
                    ajax: "{{ route('complaints.index') }}",
                    columns: [{
                            data: 'receivedBy',
                            name: 'receivedBy'
                        },
                        {
                            data: 'fullname',
                            name: 'fullname'
                        },
                        {
                            data: 'dateFiled',
                            name: 'dateFiled'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ]
                });

                //show complaint/redirect to edit page of the complaint
                var id = "";
                $("body").on('click', '.editComplaint', function() {
                    var id = $(this).data("id");

                    var token = $("meta[name='csrf-token']").attr("content");
                    $.ajax({
                        url: "{{ url('read') }}" + '/' + this.id,
                        type: 'PUT',
                        data: {
                            "notifno": this.id,
                            "_token": token,
                        },
                        success: function(data) {
                            console.log(data);
                        }
                    })

                    let url = "{{ route('complaints.edit', ':id') }}";
                    url = url.replace(':id', id);
                    document.location.href = url;
                });

                //delete complaint
                $("body").on('click', '.deleteComplaint', function() {
                    document.getElementById('audio').play();
                    var id = $(this).data("id");
                    var token = $("meta[name='csrf-token']").attr("content");
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                // url: "/deleteComplaint/" + id,
                                url: "{{ url('deleteComplaint') }}" + '/' + id,
                                type: 'DELETE',
                                data: {
                                    "id": id,
                                    "_token": token,
                                },
                                success: function() {
                                    complaintTable.draw();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Successfully deleted',
                                        showConfirmButton: false,
                                        timer: 1500
                                    })

                                }
                            });
                        }
                    })
                });
            });
        </script>
    @endpush
@endsection
