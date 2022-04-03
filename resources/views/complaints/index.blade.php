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
                            <a class="btn btn-success btn-sm" href="{{ route('complaints.create') }}"> Create New Complaint</a>
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
                            <th >NPS DOCKET NO.</th>
                            <th class="text-center">RECEIVED BY</th>
                            <th class="text-center">ASSIGNED TO</th>
                            <th class="text-center">DATE FILED</th>
                            <th class="text-center">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($complaints as $complaint)
                            <tr>
                                <td>{{ $complaint->NPSDNumber }}</td>
                                <td class="text-center">{{ $complaint->receivedBy }}</td>
                                <td class="text-center">{{ $complaint->name }}</td>
                                <td class="text-center">{{ Carbon\Carbon::parse($complaint->created_at)->format('d-M-y') }}</td>
                                <td class="text-center">
                                    <form action="{{ route('complaints.destroy', $complaint->id) }}" method="POST">
                                        {{-- <a class="btn btn-info btn-sm"
                                            href="{{ route('complaints.show', $complaint->id) }}">Show</a> --}}
                                        @can('product-edit')
                                            <a class="btn btn-info btn-sm"
                                                href="{{ route('complaints.edit', $complaint->id) }}" data-bs-toggle="tooltip" title="Show complaint"><i class="fas fa-eye"></i></a>
                                        @endcan


                                        @csrf
                                        @method('DELETE')
                                        @can('product-delete')
                                            <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Delete complaint"><i class="far fa-trash-alt"></i></button>
                                        @endcan
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- {!! $products->links() !!} --}}
        </div>
    </div>
@endsection
