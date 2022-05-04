@extends('layouts.app')

@section('content')
    {{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row mb-3 justify-content-center">
                            <div class="col-md-8 input-group">
                                <span class="input-group-text bg-primary"><i class="bi bi-person-plus-fill text-white"></i></span>
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" placeholder="Username" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3 justify-content-center">
                            <div class="col-md-8 input-group">
                                <span class="input-group-text bg-primary"><i class="bi bi-key-fill text-white"></i></span>
                                <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <!-- <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div> -->

                        <div class="row mb-0 justify-content-center">
                            <div class="col-md-8 offset-md-6">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                <!-- @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                                @endif -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}

    <div class="container-fluid h-custom">
        <div class="row">
            <div class="col-lg-2 float-left">
                <img src="{{ asset('dist/img/logo.png') }}" class="img-fluid" alt="Sample image" width="250px"
                    height="150px">
            </div>
            <div class="col-lg-10 mt-5">
                <img src="{{ asset('dist/img/test.png') }}" class="img-fluid" alt="Sample image">
            </div>
        </div>
        <div class="row d-flex justify-content-center align-items-center h-50">

            <div class="col-md-9 col-lg-6 col-xl-5">
                {{-- <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
                    class="img-fluid" alt="Sample image"> --}}
                <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                    {{-- <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                            class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                            aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                            aria-label="Slide 3"></button>
                    </div> --}}
                    <div class="carousel-inner">
                        {{-- <div class="carousel-item active">
                            <img src="{{ asset('dist/img/logo.png') }}" class="d-block w-100 h-100" alt="...">
                        </div> --}}
                        <div class="carousel-item active">
                            <img src="{{ asset('dist/img/draw2.jpeg') }}" class="d-block w-100 h-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('dist/img/draw2.jpeg') }}" class="d-block w-100 h-100" alt="...">
                        </div>
                    </div>
                    {{-- <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button> --}}
                </div>
            </div>
            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                {{-- <form>
                    <!-- Email input -->
                    <div class="form-outline mb-4">
                        <input type="email" id="form3Example3" class="form-control form-control-lg"
                            placeholder="Enter username" />
                        <label class="form-label" for="form3Example3">Username</label>
                    </div>

                    <!-- Password input -->
                    <div class="form-outline mb-3">
                        <input type="password" id="form3Example4" class="form-control form-control-lg"
                            placeholder="Enter password" />
                        <label class="form-label" for="form3Example4">Password</label>
                    </div>
                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button type="button" class="btn btn-primary btn-lg"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
                    </div>
                </form> --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="row mb-3 justify-content-center">
                        <div class="col-lg-12 input-group">
                            <span class="input-group-text bg-primary"><i
                                    class="bi bi-person-plus-fill text-white"></i></span>
                            <input id="username" type="text"
                                class="form-control form-control-lg @error('username') is-invalid @enderror"
                                placeholder="Username" name="username" value="{{ old('username') }}" required
                                autocomplete="username" autofocus>

                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-center">
                        <div class="col-lg-12 input-group">
                            <span class="input-group-text bg-primary"><i class="bi bi-key-fill text-white"></i></span>
                            <input id="password" type="password" placeholder="Password"
                                class="form-control form-control-lg @error('password') is-invalid @enderror" name="password"
                                required autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-0 justify-content-center">
                        <div class="col-md-8 offset-md-6">
                            <button type="submit" class="btn btn-primary btn-lg">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
