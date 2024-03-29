@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
@endsection

@section('javascript')
    <script src="{{ asset('js/forgot-password.js') }}"></script>
@endsection

@section('title', 'Login')

@section('content')
    <div class="container">
        <div class="left-side">
            <div class="logo">
                <img src="logo.png" alt="Logo">
                <h1>TaskFlow</h1>
            </div>
            <div class="center-message-container">
                <h2>Welcome to TaskFlow</h2>
                <p>Sign in to acess the site</p>
            </div>
            <a class="site-link" href="/">www.taskflow.com</a>
        </div>
        <div class="right-side">
            <form method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}
                <h1>Sign In</h1>

                <label for="email">
                    E-mail
                    <input id="email" type="email" name="email" value="" required="" autofocus="">
                </label>
                @if ($errors->has('email'))
                    <span class="error">
                        {{ $errors->first('email') }}
                    </span>
                @endif
                <label for="password">
                    Password
                    <input id="password" type="password" name="password" required="">
                </label>
                @if ($errors->has('password'))
                    <span class="error">
                        {{ $errors->first('password') }}
                    </span>
                @endif
                <a id="forgot-password-a" href="">Forgot password?</a>

                <div class="btns-container">
                    <button type="submit">Login</button>
                    <div class="create-account">
                        <a href="{{ route('register') }}">Create account</a>
                    </div>
                </div>
                @if (session('success'))
                    <p class="success">
                        {{ session('success') }}
                    </p>
                @endif
                <button id="google-btn" class="google-btn">
                    <svg width="36" height="36" viewBox="0 0 36 36" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <mask id="mask0_2_26" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="1" y="1"
                            width="33" height="34">
                            <path
                                d="M33.375 15H18V21.375H26.85C26.025 25.425 22.575 27.75 18 27.75C12.6 27.75 8.25 23.4 8.25 18C8.25 12.6 12.6 8.25 18 8.25C20.325 8.25 22.425 9.075 24.075 10.425L28.875 5.625C25.95 3.075 22.2 1.5 18 1.5C8.85 1.5 1.5 8.85 1.5 18C1.5 27.15 8.85 34.5 18 34.5C26.25 34.5 33.75 28.5 33.75 18C33.75 17.025 33.6 15.975 33.375 15Z"
                                fill="white" />
                        </mask>
                        <g mask="url(#mask0_2_26)">
                            <path d="M0 27.75V8.25L12.75 18L0 27.75Z" fill="#FBBC05" />
                        </g>
                        <mask id="mask1_2_26" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="1" y="1"
                            width="33" height="34">
                            <path
                                d="M33.375 15H18V21.375H26.85C26.025 25.425 22.575 27.75 18 27.75C12.6 27.75 8.25 23.4 8.25 18C8.25 12.6 12.6 8.25 18 8.25C20.325 8.25 22.425 9.075 24.075 10.425L28.875 5.625C25.95 3.075 22.2 1.5 18 1.5C8.85 1.5 1.5 8.85 1.5 18C1.5 27.15 8.85 34.5 18 34.5C26.25 34.5 33.75 28.5 33.75 18C33.75 17.025 33.6 15.975 33.375 15Z"
                                fill="white" />
                        </mask>
                        <g mask="url(#mask1_2_26)">
                            <path d="M0 8.25L12.75 18L18 13.425L36 10.5V0H0V8.25Z" fill="#EA4335" />
                        </g>
                        <mask id="mask2_2_26" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="1" y="1"
                            width="33" height="34">
                            <path
                                d="M33.375 15H18V21.375H26.85C26.025 25.425 22.575 27.75 18 27.75C12.6 27.75 8.25 23.4 8.25 18C8.25 12.6 12.6 8.25 18 8.25C20.325 8.25 22.425 9.075 24.075 10.425L28.875 5.625C25.95 3.075 22.2 1.5 18 1.5C8.85 1.5 1.5 8.85 1.5 18C1.5 27.15 8.85 34.5 18 34.5C26.25 34.5 33.75 28.5 33.75 18C33.75 17.025 33.6 15.975 33.375 15Z"
                                fill="white" />
                        </mask>
                        <g mask="url(#mask2_2_26)">
                            <path d="M0 27.75L22.5 10.5L28.425 11.25L36 0V36H0V27.75Z" fill="#34A853" />
                        </g>
                        <mask id="mask3_2_26" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="1" y="1"
                            width="33" height="34">
                            <path
                                d="M33.375 15H18V21.375H26.85C26.025 25.425 22.575 27.75 18 27.75C12.6 27.75 8.25 23.4 8.25 18C8.25 12.6 12.6 8.25 18 8.25C20.325 8.25 22.425 9.075 24.075 10.425L28.875 5.625C25.95 3.075 22.2 1.5 18 1.5C8.85 1.5 1.5 8.85 1.5 18C1.5 27.15 8.85 34.5 18 34.5C26.25 34.5 33.75 28.5 33.75 18C33.75 17.025 33.6 15.975 33.375 15Z"
                                fill="white" />
                        </mask>
                        <g mask="url(#mask3_2_26)">
                            <path d="M36 36L12.75 18L9.75 15.75L36 8.25V36Z" fill="#4285F4" />
                        </g>
                    </svg>
                    Sign in with google
                </button>
            </form>
        </div>
    </div>
    <dialog id="forgot-password-modal">
        <div class="form-container">
            <div class="logo-container">
                Forgot Password
            </div>

            <form class="form">
                @csrf
                <div class="form-group">
                    <label for="forgot-email">Email</label>
                    <input type="text" id="forgot-email" name="forgot-email" placeholder="Enter your email" required="">
                </div>
        
                <button class="form-submit-btn" id="send-email-btn" type="submit">Send Email</button>
            </form>

            <p class="signup-link">
                Don't have an account?
                <a href="{{ route('register') }}" class="signup-link link"> Sign up now</a>
            </p>
        </div>
    </dialog>
@endsection
