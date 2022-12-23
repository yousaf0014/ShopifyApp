<?php $title_for_layout = __('Reset Password');?>
@extends('layouts.login.app')

@section('content')
<div class="form-form-wrap">
    <div class="form-container">
        <div class="form-content">
            <h1 class="">{{ __('Reset Password')}}</h1>
            <p class="">Enter your email address and we will send you a link to reset your password.</p>
            <div class="form">            
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('password.email') }}">
                @csrf

                    <div class="form-group">
                        <label class="small mb-1" for="inputEmailAddress">{{ __('E-Mail Address') }}</label>
                        <input class="form-control py-4" type="email" id="inputEmailAddress @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                        <a class="small" href="{{url('/login')}}">Return to login</a>
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection



