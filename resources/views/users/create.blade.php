@extends('layouts.appLTE')


@section('content')
<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Create User</h3>
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

            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-floating">
                                                <input type="text" name="firstname" class="form-control" placeholder="First Name">
                                                <label for="floatingPassword">First Name</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-floating">
                                                <select class="form-select" name="designation" id="floatingSelect" aria-label="Floating label select example">
                                                    <option value="" disabled selected>Select</option>
                                                    <option value="Fiscal">Fiscal</option>
                                                    <option value="Encoder">Encoder</option>
                                                    <option value="Monitoring">Monitoring</option>
                                                    <option value="MTCC">MTCC</option>
                                                    <option value="RTC">RTC</option>
                                                    <option value="Receiving">Receiving</option>
                                                    <option value="Chief">Chief</option>
                                                    <option value="Admin">Admin</option>
                                                </select>
                                                <label for="floatingSelect">Designation</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-floating">
                                                <input type="text" name="lastname" class="form-control" placeholder="Last Name">
                                                <label for="floatingPassword">Last Name</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-floating">
                                                <input type="text" name="email" class="form-control" placeholder="Email">
                                                <label for="floatingPassword">Email</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-floating">
                                                <input type="text" name="middlename" class="form-control" placeholder="Middle Name">
                                                <label for="floatingPassword">Middle Name</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <!-- <div class="form-floating"> -->
                                                <!-- <select class="form-select" multiple size="5" aria-label="multiple select example">
                                                    <option selected>Open this select menu</option>
                                                    <option value="1">One</option>
                                                    <option value="2">Two</option>
                                                    <option value="3">Three</option>
                                                </select>
                                                <label for="floatingPassword">Role</label> -->
                                            <!-- </div> -->
                                            <strong>Role:</strong>
                                            {!! Form::select('roles[]', $roles, [], ['class' => 'form-control', 'multiple']) !!}
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
                                        <input type="text" name="username" class="form-control" placeholder="Username">
                                        <label for="floatingPassword">Username</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-floating">
                                        <input type="password" name="password" class="form-control" placeholder="password">
                                        <label for="floatingPassword">Password</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-floating">
                                        <input type="password" name="confirm-password" class="form-control" placeholder="confirm-password">
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
            </form>
        </div>
    </div>
</div>
@endsection