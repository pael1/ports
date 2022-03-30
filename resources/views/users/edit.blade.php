@extends('layouts.appLTE')


@section('content')
<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit User</h3>
        </div>
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="float-end p-2 mr-5">
                    <a class="btn btn-secondary btn-sm" href="{{ route('users.index') }}"> Back</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- {!! Form::model($user, ['method' => 'PATCH', 'route' => ['users.update', $user->id]]) !!} -->

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-floating">
                                                <input type="text" name="firstname" class="form-control" value="{{ $user->firstname}}">
                                                <label for="floatingPassword">First Name</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-floating">
                                                <!-- <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                                                    <option value="" disabled selected>Select</option>
                                                    <option value="Reviewer">Reviewer</option>
                                                    <option value="Online">Online</option>
                                                    <option value="Manual">Manual</option>
                                                    <option value="Chief">Chief</option>
                                                </select> -->

                                                <select class="form-select" name="designation" id="floatingSelect" aria-label="Floating label select example">
                                                    <option value="None" {{ ($user->designation == '') ? 'selected' : '' }}>Select</option>
                                                    <option value="Reviewer" {{ ($user->designation == 'Reviewer') ? 'selected' : '' }}>Reviewer</option>
                                                    <option value="Online" {{ ($user->designation == 'Online') ? 'selected' : '' }}>Online</option>
                                                    <option value="Manual" {{ ($user->designation == 'Manual') ? 'selected' : '' }}>Manual</option>
                                                    <option value="Chief" {{ ($user->designation == 'Chief') ? 'selected' : '' }}>Chief</option>
                                                </select>

                                                <label for="floatingSelect">Designation</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-floating">
                                                <input type="text" name="lastname" class="form-control" value="{{ $user->lastname}}">
                                                <label for="floatingPassword">Last Name</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-floating">
                                                <input type="text" name="email" class="form-control" value="{{ $user->email}}">
                                                <label for="floatingPassword">Email</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-floating">
                                                <input type="text" name="middlename" class="form-control" value="{{ $user->middlename}}">
                                                <label for="floatingPassword">Middle Name</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <strong>Role:</strong>
                                            {!! Form::select('roles[]', $roles, $userRole, ['class' => 'form-control', 'multiple']) !!}

                                        </div>
                                    </div>
                                    <!-- <div class="col-md-1">
                                <div class="form-group">
                                    <strong>Suffix</strong>
                                    <select class="custom-select text-center" name="suffix">
                                        <option value="" disabled selected>Select</option>
                                        <option value="Jr.">Jr.</option>
                                        <option value="Sr.">Sr.</option>
                                        <option value="II">II</option>
                                        <option value="III">III</option>
                                    </select>
                                </div>
                            </div> -->
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="form-floating">
                                        <input type="text" name="username" class="form-control" value="{{ $user->username }}">
                                        <label for="floatingPassword">Username</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-floating">
                                        <input type="password" name="password" class="form-control" value="{{ $user->password }}">
                                        <label for="floatingPassword">Password</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-floating">
                                        <input type="password" name="confirm-password" class="form-control" value="{{ $user->password }}">
                                        <label for="floatingPassword">Confirm Password</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-success btn-sm">Save</button>
                </div>

                <!-- <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Name:</strong>
                            {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Email:</strong>
                            {!! Form::text('email', null, ['placeholder' => 'Email', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Password:</strong>
                            {!! Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Confirm Password:</strong>
                            {!! Form::password('confirm-password', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Role:</strong>
                            {!! Form::select('roles[]', $roles, $userRole, ['class' => 'form-control', 'multiple']) !!}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div> -->
                <!-- {!! Form::close() !!} -->
            </form>
        </div>
    </div>
</div>




<!-- {{-- @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif --}} -->
@endsection