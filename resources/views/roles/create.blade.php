@extends('layouts.appLTE')


@section('content')
<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Create New Role</h3>
        </div>
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="float-end p-2 mr-5">
                    <a class="btn btn-secondary btn-sm" href="{{ route('roles.index') }}"> Back</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {!! Form::open(['route' => 'roles.store', 'method' => 'POST']) !!}
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <strong>Permission:</strong>
                                <br />
                                @foreach ($permission as $value)
                                <!-- <label>{{ Form::checkbox('permission[]', $value->id, false, ['class' => 'name']) }} -->

                                <div class="form-check form-switch ml-3">
                                    <input class="form-check-input" name="permission[]" value="{{ $value->id }}" type="checkbox" id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">{{ $value->name }}</label>
                                </div>

                                <!-- <label><input type="checkbox" name="permission[]" value="{{ $value->id }}" class="name">{{ $value->name }}</label> -->
                                <!-- <br /> -->
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-success btn-sm">Save</button>
                </div>
            </div>
            {!! Form::close() !!}

        </div>

    </div>
</div>
@endsection