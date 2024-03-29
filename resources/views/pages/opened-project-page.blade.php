@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/opened_project_page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}">
    <link rel="stylesheet" href="{{ asset('css/comments.css') }}">
@endsection

@section('javascript')
    <script>
        window.csrfToken = '{{ csrf_token() }}';
    </script>
    <script src="{{ asset('js/opened_project_page.js') }}"></script>
    <script src="{{ asset('js/search_typing.js') }}"></script>
    <script src="{{ asset('js/edit_task.js') }}"></script>
    <script src="{{ asset('js/comment.js') }}"></script>
    <script src="{{ asset('js/navigation.js') }}"></script>
@endsection

@section('title', 'OpenedProject')

@section('content')
    <x-navigation activePage="projects" />
    <div class="wrapper">
        <section class="content">
            <div class="top-information">
                <div class="container-left">
                    <button onclick="window.location.href='{{ route('list_projects') }}'">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_199_7200)">
                                <path
                                    d="M23.0499 31.6667C22.801 31.6675 22.5549 31.6126 22.33 31.5058C22.105 31.3991 21.9068 31.2434 21.7499 31.05L13.6999 21.05C13.4548 20.7518 13.3208 20.3777 13.3208 19.9917C13.3208 19.6056 13.4548 19.2315 13.6999 18.9333L22.0333 8.93333C22.3162 8.59296 22.7227 8.37892 23.1634 8.33829C23.6041 8.29766 24.0429 8.43376 24.3833 8.71666C24.7236 8.99956 24.9377 9.40607 24.9783 9.84679C25.0189 10.2875 24.8828 10.7263 24.5999 11.0667L17.1499 20L24.3499 28.9333C24.5538 29.178 24.6832 29.4759 24.723 29.7918C24.7628 30.1077 24.7113 30.4284 24.5745 30.716C24.4378 31.0035 24.2215 31.2458 23.9513 31.4143C23.6811 31.5828 23.3683 31.6704 23.0499 31.6667Z"
                                    fill="white" />
                            </g>
                            <defs>
                                <clippath id="clip0_199_7200">
                                    <rect width="40" height="40" fill="white" />
                                </clippath>
                            </defs>
                        </svg>
                    </button>
                    <h1>{{ $project->name }}</h1>
                    <label class="container">
                        <input data-project-id="{{ $project->project_id }}" type="checkbox"
                            {{ $isFavorite ? 'checked' : '' }} />
                        <svg height="24px" id="Layer_1" version="1.2" viewBox="0 0 24 24" width="24px"
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
                <div class="container-right">
                    <form class="search-form" action="{{ route('project_show', $project->project_id) }}" method="GET">
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
                    @php
                        $userCount = count($team);
                        $visibleCount = min(4, $userCount);
                        $extraCount = max(0, $userCount - $visibleCount);
                    @endphp

                    <div class="container-team">
                        @foreach ($teamMembers->take($visibleCount) as $user)
                            <div class="team-member">
                                <a
                                    href="{{ route('showUserProfile', ['id' => $project->project_id, 'user_id' => $user['user']->user_id]) }}">
                                    <div class="team-image-container">
                                        <div class="team-image circle" data-tooltip="{{ $user['user']->name }}">
                                            <img alt="team-member" src="{{ $user['user']->getProfileImage() }}" />
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach

                        @if ($extraCount > 0)
                            <p class="extra">+{{ $extraCount }}</p>
                        @endif
                    </div>
                    <button class="btn-expand-info">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9.33333 16C9.33333 14.5273 8.13943 13.3333 6.66667 13.3333C5.19391 13.3333 4 14.5273 4 16C4 17.4728 5.19391 18.6667 6.66667 18.6667C8.13943 18.6667 9.33333 17.4728 9.33333 16Z"
                                fill="white" />
                            <path
                                d="M18.6663 16C18.6663 14.5273 17.4724 13.3333 15.9997 13.3333C14.5269 13.3333 13.333 14.5273 13.333 16C13.333 17.4728 14.5269 18.6667 15.9997 18.6667C17.4724 18.6667 18.6663 17.4728 18.6663 16Z"
                                fill="white" />
                            <path
                                d="M28.0003 16C28.0003 14.5273 26.8064 13.3333 25.3337 13.3333C23.8609 13.3333 22.667 14.5273 22.667 16C22.667 17.4728 23.8609 18.6667 25.3337 18.6667C26.8064 18.6667 28.0003 17.4728 28.0003 16Z"
                                fill="white" />
                        </svg>
                    </button>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Task Card</th>
                        <th>Priority</th>
                        <th>Labels</th>
                        <th>Assignees</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $i => $task)
                        <tr>
                            <td class="task-card" itemid="{{ $task->task_id }}">{{ $task->name }}</td>
                            <td class="priority">
                                <div data-priority="{{ $task->priority }}">{{ $task->priority }}</div>
                            </td>
                            <td class="labels">
                                @foreach ($task->labels as $label)
                                    <span>{{ $label->name }}</span>
                                @endforeach
                            </td>
                            <td class="assignees">
                                <div class="container-assigned">
                                    @foreach ($task->assignees as $assignee)
                                        <div class="team-image circle">
                                            <img alt="team-member" src="{{ $assignee->getProfileImage() }}" />
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="due-date">{{ $task->due_date }}</td>
                            <td class="status" data-status="Done">{{ $task->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="btns-container">
                @if ($project->archived && $role[0]->role === 'Project Coordinator')
                    <button class="btn-unarchive-proj" id="unarchiveProjectBtn"
                        data-project-id="{{ $project->project_id }}">Unarchive Project</button>
                @endif
                @if ($project->archived && $role[0]->role !== 'Project Coordinator')
                    <button class="btn-leave-proj" id="leaveProjectBtn" data-project-id="{{ $project->project_id }}">Leave
                        Project</button>
                @endif
                @if (!$project->archived && $role[0]->role === 'Project Coordinator')
                    <button class="btn-archive-proj" id="archiveProjectBtn"
                        data-project-id="{{ $project->project_id }}">Archive Project</button>
                    <button class="btn-edit-details">Edit Details</button>
                    <button class="btn-manage-team">Manage Team</button>
                    <button class="btn-create-task">Create Task</button>
                @endif
                @if (!$project->archived && $role[0]->role !== 'Project Coordinator')
                    <button class="btn-leave-proj" id="leaveProjectBtn" data-project-id="{{ $project->project_id }}">Leave
                        Project</button>
                    <button class="btn-create-task">Create Task</button>
                @endif
            </div>
        </section>
    </div>
    <div class="hidden project-details">
        <button class="btn-close-details">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M11.6581 11.6772C11.0072 12.3281 11.0072 13.3834 11.6581 14.0343L17.6333 20.0095L11.6581 25.9848C11.0072 26.6356 11.0072 27.691 11.6581 28.3418C12.309 28.9926 13.3642 28.9926 14.0151 28.3418L19.9903 22.3665L25.9657 28.3418C26.6165 28.9926 27.6718 28.9926 28.3227 28.3418C28.9735 27.691 28.9735 26.6356 28.3227 25.9848L22.3473 20.0095L28.3227 14.0343C28.9735 13.3834 28.9735 12.3281 28.3227 11.6773C27.6717 11.0264 26.6165 11.0264 25.9657 11.6773L19.9903 17.6525L14.0151 11.6772C13.3642 11.0264 12.309 11.0264 11.6581 11.6772Z"
                    fill="white" />
            </svg>
        </button>
        <h2>About the project:</h2>
        <p>
            {{ $project->description }}
        </p>
        <h2>Team</h2>
        <div class="details-team">
            @foreach ($teamMembers as $user)
                <a
                    href="{{ route('showUserProfile', ['id' => $project->project_id, 'user_id' => $user['user']->user_id]) }}">
                    <div class="team-image circle">
                        <img alt="team-member" src="{{ $user['user']->getProfileImage() }}" />
                    </div>
                    {{ $user['user']->username }}
                </a>
            @endforeach
        </div>
    </div>

    @foreach ($tasks as $i => $task)
        <x-task-modal :project="$project" :task="$task" :team="$team" />
    @endforeach

    @if (!$project->archived)
        <dialog id="modal-edit-project">
            <form method="POST" action="{{ route('edit_project', ['projectId' => $project->project_id]) }}">
                @csrf
                @method('PUT')
                <h2>Edit Details:</h2>
                <label for="projectName"> Project Name: </label>
                <input id="projectName" name="name" value="{{ $project->name }}" type="text" />
                <label for="projectDescription"> Description: </label>
                <textarea id="projectDescription" name="description">{{ $project->description }}</textarea>

                <input type="submit" value="Save" />
            </form>
        </dialog>
        <dialog id="modal-manage-team">
            <div class="container-manage-team">
                <h2>Manage Team:</h2>
                <form action="{{ route('send_invite') }}" method="post">
                    @csrf
                    <input name="project_id" value="{{ $project->project_id }}" hidden>
                    <div class="autocomplete-wrapper">
                        <label for="invite-member">
                            Invite Member:
                            <input data-project-id="{{ $project->project_id }}" id="invite-member" name="username"
                                placeholder="Username" type="text" required autocomplete="off" />
                        </label>
                        <ul id="autocomplete-list" class="autocomplete-list"></ul>
                    </div>
                    <input type="submit" value="Send Invite" />
                </form>
                <h3>Team:</h3>
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teamMembers as $user)
                            <tr>
                                <td class="image">
                                    <div class="team-image circle">
                                        <img alt="team-member" src="{{ $user['user']->getProfileImage() }}" />
                                    </div>
                                </td>
                                <td class="name">{{ $user['user']->name }}</td>
                                <td class="username">{{ $user['user']->username }}</td>
                                <td class="email">{{ $user['user']->email }}</td>
                                <td class="role">{{ $user['role'] }}</td>
                                <td class="actions">
                                    <div>
                                        <button class="btn-promote-member" data-project-id="{{ $project->project_id }}"
                                            data-user-id="{{ $user['user']->user_id }}">Promote</button>
                                        <button class="btn-remove-member" data-project-id="{{ $project->project_id }}"
                                            data-user-id="{{ $user['user']->user_id }}">
                                            <svg width="10" height="12" viewBox="0 0 10 12" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M1.41131 10.6737C1.41131 11.0534 1.96881 11.5747 2.34048 11.5747H7.91553C8.2872 11.5747 8.84471 11.0534 8.84471 10.6737V3.46559H1.41131V10.6737ZM9.58805 1.40992H7.60581L6.61469 0.221924H3.64133L2.65021 1.40992H0.667969V2.59791H9.58805V1.40992Z"
                                                    fill="white" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </dialog>
        <dialog id="modal-create-task">
            <div>
                <h2>Create Task:</h2>
                <form id="taskForm">
                    @csrf
                    <label for="name">Task Name:</label>
                    <input type="text" name="name" id="name" />

                    <label for="description">Description:</label>
                    <textarea name="description" class="description"></textarea>

                    <label for="priority">Priority:
                        <select name="priority" class="priority">
                            <option value="High">High</option>
                            <option value="Medium">Medium</option>
                            <option value="Low">Low</option>
                        </select>
                    </label>

                    <label for="user_assigned_id">Assignee:
                        <select name="user_assigned_id" id="user_assigned_id">
                            @foreach ($team as $member)
                                <option value={{ $member->user_id }}>{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label for="add-labels" class="add-labels">Labels:
                        <input type="text" class="add-labels-input" id="add-labels" placeholder="Enter label...">
                        <button class="add-label-button">
                            <svg width="27" height="26" viewBox="0 0 27 26" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.7539 20.2669V5.63623" stroke="white" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path d="M6.08887 12.9517H21.419" stroke="white" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </label>

                    <input type="number" name="project_id" value="{{ $project->project_id }}" hidden />

                    <div class="container-labels">
                    </div>

                    <label for="due_date">Due Date:
                        <input type="date" name="due_date" id="due_date" />
                    </label>

                    <input type="submit" value="Create Task" />
                </form>
            </div>
        </dialog>
    @endif

@endsection
