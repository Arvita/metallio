@extends('layouts.login')

@section('content')
<div class="wrapper wrapper-login wrapper-login-full p-0">
    <div class="login-aside w-50 d-flex flex-column align-items-center justify-content-center text-center bg-black-gradient">
        <img src="{{ asset('/assets/img/logo.png') }}">
        <h1 class="title fw-bold text-white mb-3">Welcome to Tryout Metallio 2022</h1>
    </div>
    <div class="login-aside w-50 d-flex align-items-center justify-content-center bg-white">
        <div class="container container-login container-transparent animated fadeIn">
            <h3 class="text-center">Sign In</h3>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="login-form">
                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="placeholder"><b>Email</b></label>
                        <input id="email" name="email" type="email" class="form-control" required
                            value="{{ old('email') }}">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="placeholder"><b>Password</b></label>
                        <div class="position-relative">
                            <input id="password" name="password" type="password" class="form-control" required>
                            <div class="show-password">
                                <i class="icon-eye"></i>
                            </div>
                        </div>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group form-action-d-flex mb-3">
                        <div class="custom-control custom-checkbox">
                        </div>
                        <button type="submit" class="btn btn-info col-md-5 float-right mt-3 mt-sm-0 fw-bold">Sign
                            In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
