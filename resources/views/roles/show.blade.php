@extends('layouts.appLTE')


@section('content')
    <div class="content-wrapper">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Show Role</h3>
            </div>
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="float-end p-2 mr-5">
                        <a class="btn btn-secondary btn-sm" href="{{ route('roles.index') }}"> Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $role->name }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Permissions:</strong>
                            <br />
                            @foreach ($permission as $value)
                                <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, ['class' => 'name']) }}
                                    {{ $value->name }}</label>
                                <br />
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
