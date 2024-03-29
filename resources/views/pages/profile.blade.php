@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}">
@endsection

@section('javascript')
    <script src="{{ asset('js/navigation.js') }}"></script>
    <script src="{{ asset('js/upload-image.js') }}"></script>
@endsection

@section('title', 'Profile')

@section('content')
    <x-navigation activePage="profile" />
    <div class="wrapper">
        <section class="content">
            <div class="profile-upload-wrapper">
                <div class="image-wrapper">
                    <img src="{{ $user->getProfileImage() }}" alt="profile-img">
                </div>
                <button class="upload-image" id="upload-image-btn">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M20 26.6667C22.7615 26.6667 25 24.4282 25 21.6667C25 18.9052 22.7615 16.6667 20 16.6667C17.2385 16.6667 15 18.9052 15 21.6667C15 24.4282 17.2385 26.6667 20 26.6667Z"
                            stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M5 28V15.3333C5 13.4665 5 12.5331 5.36332 11.82C5.68288 11.1928 6.19282 10.6829 6.82003 10.3633C7.53307 10 8.4665 10 10.3333 10H12.0911C12.296 10 12.3984 10 12.4929 9.98917C12.9861 9.93269 13.4284 9.65932 13.6995 9.24349C13.7514 9.16379 13.7973 9.07214 13.8889 8.88889C14.0722 8.52237 14.1638 8.33911 14.2677 8.17971C14.8098 7.34804 15.6945 6.80131 16.6808 6.68832C16.8698 6.66667 17.0747 6.66667 17.4845 6.66667H22.5155C22.9253 6.66667 23.1302 6.66667 23.3192 6.68832C24.3055 6.80131 25.1902 7.34804 25.7323 8.17971C25.8362 8.33909 25.9278 8.52242 26.1112 8.88889C26.2027 9.07216 26.2485 9.16379 26.3005 9.24349C26.5717 9.65932 27.0138 9.93269 27.507 9.98917C27.6017 10 27.704 10 27.909 10H29.6667C31.5335 10 32.467 10 33.18 10.3633C33.8072 10.6829 34.3172 11.1928 34.6367 11.82C35 12.5331 35 13.4665 35 15.3333V28C35 29.8668 35 30.8003 34.6367 31.5133C34.3172 32.1405 33.8072 32.6505 33.18 32.97C32.467 33.3333 31.5335 33.3333 29.6667 33.3333H10.3333C8.4665 33.3333 7.53307 33.3333 6.82003 32.97C6.19282 32.6505 5.68288 32.1405 5.36332 31.5133C5 30.8003 5 29.8668 5 28Z"
                            stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
            <form id="upload-image-form" method="POST" action="/file/upload" enctype="multipart/form-data">
                @csrf
                <input id="fileInput" name="file" type="file" style="display: none;" required>
                <input name="id" type="number" value="{{ $user->user_id }}" hidden>
                <input name="type" type="text" value="profiles" hidden>
            </form>
            <form id="update-profile" method="POST" action="{{ route('update_profile', ['id' => $user->user_id]) }}">
                @csrf
                <div class="info-wrapper">
                    <div class="user-info">
                        <label for="name">Name:</label>
                        <input id="name" name="name" type="text" value="{{ $user->name }}">
                        <label for="username">Username:</label>
                        <input id="username" name="username" type="text" value="{{ $user->username }}">
                        <label for="email">Email:</label>
                        <input id="email" name="email" type="text" value="{{ $user->email }}">
                    </div>
                    <div class="password-mod">
                        <label for="current-pass">Current Password:</label>
                        <input id="current-pass" type="password" name="current_password">
                        <label for="new-pass">New Password:</label>
                        <input id="new-pass" name="new_password" type="password" minlength="8">
                        <label for="confirm-pass">Confirm New Password:</label>
                        <input id="confirm-pass" name="confirm_password" type="password">
                    </div>
                </div>
                <input class="updateProf-btn" type="submit" value="Update Profile">
            </form>

        </section>
    </div>
@endsection
