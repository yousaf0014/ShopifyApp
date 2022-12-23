<?php $title_for_layout = __('Verify');?>
@extends('layouts.login.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                    <div class="col-lg-6">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">{{ __('Verify Your Email Address') }}</h1>
                            </div>
                            @if (session('resent'))
                                <div class="alert alert-success" role="alert">
                                    {{ __('A fresh verification link has been sent to your email address.') }}
                                </div>
                            @endif
                            {{ __('Before proceeding, please check your email for a verification link.') }}
                            {{ __('If you did not receive the email') }},
                            <form method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-user btn-block">{{ __('click here to request another') }}</button>
                            </form>
                            <hr>
                            <div class="text-center">                                
                                @if (Route::has('password.request'))                    
                                    <a href="{{route('password.request')}}" class="small">{{ __('Forgot Your Password?') }}</a>
                                @endif   
                            </div>
                            <div class="text-center">
                                <a class="small" href="{{url('/login')}}">{{ __('Already have an account? Login!')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
