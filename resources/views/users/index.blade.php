@extends('layouts.appLTE')


@section('content')
    <div class="content-wrapper">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Users</h3>
            </div>
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="float-end p-2 mr-5">
                        <a class="btn btn-success btn-sm" href="{{ route('users.create') }}"> Create New User</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="example1" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th width="180px" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $user)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if (!empty($user->getRoleNames()))
                                        @foreach ($user->getRoleNames() as $roles)
                                            <label class="badge bg-warning text-dark">{{ $roles }}</label>
                                        @endforeach
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-info btn-sm" href="{{ route('users.show', $user->id) }}">Show</a>
                                    <a class="btn btn-primary btn-sm" href="{{ route('users.edit', $user->id) }}">Edit</a>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id], 'style' => 'display:inline']) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
