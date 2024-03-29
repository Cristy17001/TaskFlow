@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/projects-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}">
@endsection

@section('javascript')
    <script src="{{ asset('js/navigation.js') }}"></script>
    <script src="{{ asset('js/projects.js') }}"></script>
@endsection
@section('title', 'Projects')

@section('content')
    <x-navigation activePage="projects" />
    <div class="wrapper">
        <section class="content">
            <h1>Projects</h1>
            <section class="section-filtering">
                <form class="form-search-projects" action="{{ route('list_projects') }}" method="GET">
                    <input type="text" name="search" placeholder="Search For Project" value="{{ request('search') }}" />
                    <button class="btn-search">
                        <svg width="34" height="36" viewBox="0 0 34 36" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M14.8497 25.4437C19.9266 25.4437 24.0422 21.0881 24.0422 15.7152C24.0422 10.3423 19.9266 5.98676 14.8497 5.98676C9.77283 5.98676 5.65723 10.3423 5.65723 15.7152C5.65723 21.0881 9.77283 25.4437 14.8497 25.4437Z"
                                stroke="white" stroke-width="2.5" stroke-linejoin="round" />
                            <path
                                d="M27.785 30.4629C28.0612 30.7551 28.5089 30.7551 28.7851 30.4629C29.0612 30.1706 29.0612 29.6968 28.7851 29.4045L27.785 30.4629ZM28.7851 29.4045L22.2424 22.4802C21.9555 22.1767 21.4725 22.1767 21.1856 22.4802C20.9209 22.7604 20.9209 23.1986 21.1856 23.4788L27.785 30.4629L28.7851 29.4045Z"
                                fill="white" />
                        </svg>
                    </button>
                </form>
                <form class="form-filter-options">
                    <label for="filter">
                        <select id="filter" name="filter">
                            <option value="All" selected>My Projects</option>
                            <option value="Favorite">Favorite</option>
                            <option value="Member">Member</option>
                            <option value="Coordinator">Coordinator</option>
                            <option value="Archived">Archived</option>
                        </select>
                    </label>
                </form>
                <button class="btn-create-project">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M16 30C8.268 30 2 23.73 2 16C2 8.27 8.268 2 16 2C23.732 2 30 8.27 30 16C30 23.73 23.732 30 16 30ZM16 0C7.163 0 0 7.16 0 16C0 24.84 7.163 32 16 32C24.837 32 32 24.84 32 16C32 7.16 24.837 0 16 0ZM22 15H17V10C17 9.45 16.553 9 16 9C15.447 9 15 9.45 15 10V15H10C9.447 15 9 15.45 9 16C9 16.55 9.447 17 10 17H15V22C15 22.55 15.447 23 16 23C16.553 23 17 22.55 17 22V17H22C22.553 17 23 16.55 23 16C23 15.45 22.553 15 22 15Z"
                            fill="white" />
                    </svg>
                    Create Project
                </button>
            </section>
            <section class="section-project-cards">
                @foreach ($projects as $project)
                    <a href="{{ route('project_show', ['projectId' => $project->project_id]) }}">
                        <div class="project-card" data-archived="{{ $project->archived }}">
                            <h2>{{ $project->name }}</h2>
                            <p class="description">{{ $project->description }}</p>
                            <p class="creator">-by {{ $project->creator_username }}</p>
                            <label class="container">
                                <input data-project-id="{{ $project->project_id }}" type="checkbox" disabled
                                    {{ $project->isFavorite ? 'checked' : '' }} />
                                <svg height="24px" version="1.2" viewBox="0 0 24 24" width="24px"
                                    xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <g>
                                        <g>
                                            <path
                                                d="M9.362,9.158c0,0-3.16,0.35-5.268,0.584c-0.19,0.023-0.358,0.15-0.421,0.343s0,0.394,0.14,0.521    c1.566,1.429,3.919,3.569,3.919,3.569c-0.002,0-0.646,3.113-1.074,5.19c-0.036,0.188,0.032,0.387,0.196,0.506    c0.163,0.119,0.373,0.121,0.538,0.028c1.844-1.048,4.606-2.624,4.606-2.624s2.763,1.576,4.604,2.625    c0.168,0.092,0.378,0.09,0.541-0.029c0.164-0.119,0.232-0.318,0.195-0.505c-0.428-2.078-1.071-5.191-1.071-5.191    s2.353-2.14,3.919-3.566c0.14-0.131,0.202-0.332,0.14-0.524s-0.23-0.319-0.42-0.341c-2.108-0.236-5.269-0.586-5.269-0.586    s-1.31-2.898-2.183-4.83c-0.082-0.173-0.254-0.294-0.456-0.294s-0.375,0.122-0.453,0.294C10.671,6.26,9.362,9.158,9.362,9.158z">
                                            </path>
                                        </g>
                                    </g>
                                </svg>
                            </label>
                        </div>
                    </a>
                @endforeach
            </section>
        </section>
    </div>
@endsection
