<?php $title_for_layout = __('Register');?>
@extends('layouts.login.app')
@section('content')
<div class="form-form-wrap">
    <div class="form-container">
        <div class="form-content">
            <h1 class="">Register</h1>
            <p class="signup-link register">Already have an account? <a href="{{route('login')}}">Log in</a></p>
            <p>
            </p>
            <form method="POST" class="text-left" action="{{ route('register') }}">
                @csrf
                <div id="firstname-field" class="field-wrapper input">
                    <label for="username">Name</label>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <input type="text" name="name" placeholder="{{ __('Name') }}" class="form-control form-control-user @error('name') is-invalid @enderror" />
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                </div>
                <div id="email-field" class="field-wrapper input">
                    <label for="email">EMAIL</label>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign register"><circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path></svg>
                    <input id="inputEmailAddress" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus aria-describedby="emailHelp" placeholder="{{ __('E-Mail Address') }}">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div id="password-field" class="field-wrapper input mb-2">
                    <div class="d-flex justify-content-between">
                        <label for="password">PASSWORD</label>
                        <a href="{{route('password.request')}}" class="forgot-pass-link">Forgot Password?</a>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    
                    <input type="password" name="password" placeholder="{{ __('Password') }}" class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div id="confirm-field" class="field-wrapper input mb-2">
                    <div class="d-flex justify-content-between">
                        <label for="password">Confirm Password</label>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    <input type="password" placeholder="{{ __('Confirm Password') }}" class="form-control form-control-user" id="password-confirm" name="password_confirmation" required autocomplete="new-password">
                    @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div id="email-field" class="field-wrapper input">
                    <div class="d-flex justify-content-between">
                        <label for="email">Type</label>
                    </div>
                    <svg class="feather feather-lock" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    
                    <select id="type" class="form-control form-control-user @error('type') is-invalid @enderror" name="type" required="" placeholder="{{ __('User Type') }}">
                        <option value="">--User Type--</option>
                        <option value="customer">Customer</option>
                        <option value="order_desk">Order Desk</option>
                    </select>
                    @error('type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary btn-user btn-block">{{ __('Register') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection