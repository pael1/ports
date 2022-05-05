@extends('layouts.app')

@section('content')
    <div class="container-fluid h-custom">
        <div class="row">
            {{-- <div id="mainlogo">
                <img src="{{ asset('dist/img/logo.png') }}" class="d-none d-sm-block d-lg-block" />
                <img src="{{ asset('dist/img/test.png') }}" class="d-sm-none" />
            </div> --}}
            <div class="col-sm-2 col-md-2 col-lg-2 float-left d-none d-sm-block d-lg-block">
                <img src="{{ asset('dist/img/logo.png') }}" class="img-fluid" alt="Sample image" width="250px"
                    height="150px">
            </div>
            <div class="col-sm-10 col-md-10 col-lg-10 mt-5 d-none d-sm-block d-lg-block">
                <img src="{{ asset('dist/img/test.png') }}" class="img-fluid" alt="Sample image">
            </div>
            <div class="col-lg-2 d-sm-none text-center">
                <img src="{{ asset('dist/img/logo.png') }}" class="img-fluid" alt="Sample image" width="150px"
                    height="50px">
                <div class="mb-3"></div>
            </div>
        </div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-10 col-lg-10 mt-5 d-none d-sm-block d-lg-block">
                <div class="mb-3"></div>
            </div>
            <div class="col-lg-6 col-xl-5">
                {{-- <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ asset('dist/img/draw2.jpeg') }}" class="d-block w-100 h-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('dist/img/draw2.jpeg') }}" class="d-block w-100 h-100" alt="...">
                        </div>
                    </div>
                </div> --}}
                {{-- <figure class="bg-white p-3 rounded" style="border-left: .25rem solid #c0314b;"> --}}
                <h6 class="text-white">Advice # <span id="replace-me-no"></span></h6>
                {{-- <i class="fas fa-quote-left fa-lg text-warning me-2"></i>
                    <p id="replace-me"></p> --}}
                <i class="fas fa-quote-left fa-xs text-white"></i>
                <span class="lead font-italic text-white" id="replace-me"></span>
                <i class="fas fa-quote-right fa-xs text-white"></i>
                {{-- </figure> --}}
                <div class="col-sm-2 col-md-2 col-lg-2 d-none d-sm-block d-lg-block">
                    <div class="mb-3"></div>
                </div>
                <div class="d-sm-none text-center">
                    <div class="mb-3"></div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-4 offset-xl-1">
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
                    <div class="row justify-content-center">
                        <div class="col-md-8 text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script>
        $(function() {
            $.ajax({
                url: "https://api.adviceslip.com/advice",
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    document.getElementById('replace-me').innerText = data.slip.advice;
                    document.getElementById('replace-me-no').innerText = data.slip.id;
                }
            })
            setInterval(() => {
                $.ajax({
                    url: "https://api.adviceslip.com/advice",
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        document.getElementById('replace-me').innerText = data.slip.advice;
                        document.getElementById('replace-me-no').innerText = data.slip.id;
                    }
                })
            }, 10000);
            // $("p").fadeOut("slow");

        })
    </script>
@endsection
