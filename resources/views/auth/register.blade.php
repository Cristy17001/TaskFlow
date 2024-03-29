@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('title', 'Register')

@section('content')
<div class="container">
  <div class="left-side">
      <form method="POST" action="{{ route('register') }}">
          {{ csrf_field() }}
          <h1>Sign Up</h1>
          <label>
            Name 
            <input id="name" type="text" name="name" value="" required="" >
          </label>
          <label for="username">
              Username
              <input id="username" type="text" name="username" value="" required="">
          </label>
          <label for="email">
              E-mail
              <input id="email" type="email" name="email" value="" required="" >
          </label>
          <label for="password">
              Password
              <input id="password" type="password" name="password" required="">
          </label>
          <label for="password_confirmation">
            Confirm Password
            <input id="password_confirmation" type="password" name="password_confirmation" required="">
        </label>        
          <p class="AlreadyAccountMobile">Already have account? <a href="{{ route('login') }}">Sign In</a></p>
          <button class="Register-btn" type="submit">Register</button>
      </form>
  </div>
  <div class="right-side">
      <div class="logo">
          <img src="logo.png" alt="Logo">
          <h1>TaskFlow</h1>
      </div>
      <div class="center-message-container">
          <h2>Already have account?</h2>
          <a href="{{ route('login') }}">
            <button>
              Sign In
            </button>
          </a>
      </div>
      <a class="site-link" href="/">www.taskflow.com</a>
  </div>
</div>
@endsection