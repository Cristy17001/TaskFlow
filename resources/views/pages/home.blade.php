@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/home-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}">
@endsection

@section('javascript')
    <script src="{{ asset('js/navigation.js') }}"></script>
@endsection

@section('title', 'Home')

@section('content')
    <x-navigation activePage="home" />
    <div class="wrapper">
        <section class="content">
            <h1>My work</h1>
            <div class="top-of-table">
                <form class="search-form" action="{{ route('home') }}" method="GET">
                    <input type="text" name="search" placeholder="Search" value="{{ request('search') }}">
                    <button type="submit">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_1113_2)">
                                <path
                                    d="M6.59996 1.89997C-1.60004 7.29997 -2.00004 16.9 5.59996 22.9C8.59996 25.3 14.4 25.7 18.5 23.8C20.9 22.7 21.4 23 26 27.5C28.7 30.2 31.3 32.1 31.7 31.7C32.1 31.3 30.2 28.7 27.5 26C23 21.4 22.7 20.9 23.8 18.5C25.7 14.4 25.3 8.59997 22.9 5.59997C18.6 0.099969 11.8 -1.50003 6.59996 1.89997ZM19.4 5.59997C24.5 10.8 22.5 20 15.7 21.8C8.69996 23.6 2.99996 19.6 2.99996 12.8C2.99996 4.19997 13.4 -0.300031 19.4 5.59997Z"
                                    fill="white" />
                            </g>
                            <defs>
                                <clipPath id="clip0_1113_2">
                                    <rect width="32" height="32" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </button>
                </form>
            </div>
            @if (isset($tasks) && sizeof($tasks) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Task Title</th>
                            <th>Description</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Assignees</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            <tr>
                                <td class="project">{{ $task->project_name }}</td>
                                <td class="title">{{ $task->name }}</td>
                                <td class="description">{{ $task->description }}</td>
                                <td class="priority">
                                    <div data-priority = "{{ $task->priority }}">{{ $task->priority }}</div>
                                </td>
                                <td class="status">{{ $task->status }}</td>
                                <td class="assignees">
                                    <div class="container-assigned">
                                        @foreach ($task->assignees as $assignee)
                                            <div class="team-image-container">
                                                <div class="team-image circle" data-tooltip="{{ $assignee->name }}">
                                                    <img alt="team-member"
                                                        src="{{ $assignee->user->getProfileImage() }}" />
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="view"><a href="/projects/{{ $task->project_id }}#{{ $task->task_id }}"
                                        class="btn btn-info">View</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $tasks->links() }}
            @else
                <p class="no-task-assigned">You dont have any task assigned to you!</p>
            @endif
        </section>
    </div>
@endsection
