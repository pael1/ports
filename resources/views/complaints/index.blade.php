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
                            <a class="btn btn-success btn-sm" href="{{ route('complaints.create') }}"> Create New Product</a>
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


                <table id="example1" class="table">
                    <thead>
                        <tr>
                            <th>No1</th>
                            <th>Nam1e</th>
                            <th>Details</th>
                            <th width="280px">Act1ion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($complaints as $complaint)
                            <tr>
                                <td>{{ $complaint->NPSDNumber }}</td>
                                <td>{{ $complaint->formType }}</td>
                                <td>{{ $complaint->placeofCommission }}</td>
                                <td class="text-center">
                                    <form action="{{ route('complaints.destroy', $complaint->id) }}" method="POST">
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('complaints.show', $complaint->id) }}">Show</a>
                                        @can('product-edit')
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ route('complaints.edit', $complaint->id) }}">Edit</a>
                                        @endcan


                                        @csrf
                                        @method('DELETE')
                                        @can('product-delete')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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
