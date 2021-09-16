@extends('layouts.auth')

@section('title', 'Login')

@push('page-style')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/authentication.css') }}">
@endpush

@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- login page start -->
            <section id="auth-login" class="row flexbox-container">
                <div class="col-xl-4 col-11">
                    <div class="card bg-authentication mb-0">
                        <div class="row m-0">
                            <div class="col-12 d-md-block text-center align-self-center p-3">
                                <div class="card-content">
                                    <img class="img-fluid" src="{{ asset('app-assets/images/logo/logo_big.jpg') }}" alt="InPlace Auction Logo">
                                </div>
                            </div>
                            <!-- left section-login -->
                            <div class="col-12 px-0">
                                <div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
                                    <div class="card-header pb-1">
                                        <div class="card-title">
                                            <h4 class="text-center mb-2">Welcome Back</h4>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">

                                            @isset($url)
                                                <form method="POST" action='{{ url("login/$url") }}'>
                                                    @else
                                                <form method="POST" action="{{ route('login') }}">
                                            @endisset

                                                @csrf

                                                <div class="form-group mb-50">
                                                    <label for="email" class="text-bold-600">{{ __('E-Mail Address') }}</label>

                                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                                        @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                        @enderror
                                                </div>

                                                <div class="form-group mb-50">
                                                    <label for="password" class="text-bold-600">{{ __('Password') }}</label>

                                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                                        @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                        @enderror
                                                </div>
                                                  <!-- d-flex flex-md-row flex-column -->
                                                <div class="form-group row justify-content-between align-items-center">
                                                    <div class="text-center col-12">
                                                        <div class="checkbox checkbox-sm">
                                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                            <label class="form-check-label" for="remember">
                                                                {{ __('Remember Me') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="text-center col-12">
                                                        @if (Route::has('password.request'))
                                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                                {{ __('Forgot Your Password?') }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary glow w-100 position-relative">
                                                    {{ __('Login') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- right section image -->
                        </div>
                    </div>
                </div>
            </section>
            <!-- login page ends -->
        </div>
    </div>

@endsection
