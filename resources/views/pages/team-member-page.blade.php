@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/team-member-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}">
@endsection

@section('javascript')
    <script src="{{ asset('js/navigation.js') }}"></script>
@endsection

@section('title', 'TeamMember')

@section('content')
    <x-navigation activePage="projects" />
    <div class="wrapper">
        <section class="content">
            <section class="container-user-info">
                <div class="image-wrapper">
                    <img src="{{ $user->getProfileImage() }}">
                </div>
                <div class="main-info">
                    <div>
                        <label for="name">Name:</label>
                        <input id="name" type="text" value="{{ $user->name }}" disabled>
                    </div>
                    <div>
                        <label for="username">Username:</label>
                        <input id="username" type="text" value="{{ $user->username }}" disabled>
                    </div>
                    <div>
                        <label for="email">Email:</label>
                        <input id="email" type="text" value="{{ $user->email }}" disabled>
                    </div>
                </div>
            </section>
            <h3>Currently Working on:</h3>
            <section class="section-working">
                @foreach ($projects as $project)
                    <div class="working-project">{{ $project }}</div>
                @endforeach
            </section>
        </section>
    </div>
@endsection
