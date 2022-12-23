@extends('layouts.login.app')
@section('content')
<!-- Outer Row -->
<div class="form-form-wrap">
    <div class="form-container">
        <div class="form-content">
            <h1 class="">Sign In</h1>
            <p class="">Log in to your account to continue.</p>
            <form action="{{ route('login') }}" class="text-left" method="POST">
                @csrf    
                <div class="form">
                    <div id="username-field" class="field-wrapper input">
                        <label for="username">USERNAME</label>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        
                        <input id="inputEmailAddress" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus aria-describedby="emailHelp" placeholder="{{ __('E-Mail Address') }}">
                        @error('ecmail')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div id="password-field" class="field-wrapper input mb-2">
                        <div class="d-flex justify-content-between">
                            <label for="password">PASSWORD</label>
                            @if (Route::has('password.request'))                    
                                <a href="{{route('password.request')}}" class="small">Forgot Password?</a>
                            @endif
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        
                        <input type="password" placeholder="{{ __('Password') }}" id="inputPassword" type="password" class="form-control @error('password') is-invalid @enderror" name="password" >
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $password }}</strong>
                            </span>
                        @enderror
                        
                    </div>
                    <div class="d-sm-flex justify-content-between">
                        <div class="field-wrapper">
                            <button type="submit" class="btn btn-primary" value="">Log In</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
