@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
@endsection

@section('javascript')
    <script src="{{ asset('js/navigation.js') }}"></script>
@endsection

@section('title', 'Notifications')

@section('content')
    <x-navigation activePage="notifications" />
    <div class="wrapper">
        <section class="content">
            <section class="section-title">
                <h1>My Notifications</h1>
            </section>
            <section class="section-notifications">
                @foreach ($notifications as $notification)
                    @if ($notification->type === 'Project Welcome')
                        <div class="notification-card welcome-to-project">
                            <div class="title-description">
                                <h3>Welcome Aboard!</h3>
                                <p>You just joined <b>{{ $notification->project_name }}</b>.</p>
                            </div>
                            <div class="time-actions">
                                <p class="time"><b>{{ $notification->date }}</b></p>
                                <div class="actions">
                                    <form method="POST"
                                        action="{{ route('notification_delete', ['id' => $notification->notification_id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-delete"
                                            onclick="return confirm('Are you sure you want to delete this notification?')">
                                            <svg width="19" height="24" viewBox="0 0 19 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M1.54386 21.7076C1.54386 22.4962 2.70175 23.5789 3.47368 23.5789H15.0526C15.8246 23.5789 16.9825 22.4962 16.9825 21.7076V6.73684H1.54386V21.7076ZM18.5263 2.46737H14.4094L12.3509 0H6.17544L4.11696 2.46737H0V4.93474H18.5263V2.46737Z"
                                                    fill="white" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($notification->type === 'Task Assignment')
                        <div class="notification-card new-task-assigned">
                            <div class="title-description">
                                <h3>New Task Assigned!</h3>
                                <p>You have a new task: <b>{{ $notification->task_name }}</b> in
                                    <b>{{ $notification->project_name }}</b>.
                                </p>
                            </div>
                            <div class="time-actions">
                                <p class="time"><b>{{ $notification->date }}</b></p>
                                <div class="actions">
                                    <form method="POST"
                                        action="{{ route('notification_delete', ['id' => $notification->notification_id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-delete"
                                            onclick="return confirm('Are you sure you want to delete this notification?')">
                                            <svg width="19" height="24" viewBox="0 0 19 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M1.54386 21.7076C1.54386 22.4962 2.70175 23.5789 3.47368 23.5789H15.0526C15.8246 23.5789 16.9825 22.4962 16.9825 21.7076V6.73684H1.54386V21.7076ZM18.5263 2.46737H14.4094L12.3509 0H6.17544L4.11696 2.46737H0V4.93474H18.5263V2.46737Z"
                                                    fill="white" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($notification->type === 'Task Completed')
                        <div class="notification-card task-complete">
                            <div class="title-description">
                                <h3>Task Completed!</h3>
                                <p><b>{{ $notification->task_name }}</b> in <b>{{ $notification->project_name }}</b> is
                                    done.</p>
                            </div>
                            <div class="time-actions">
                                <p class="time"><b>{{ $notification->date }}</b></p>
                                <div class="actions">
                                    <form method="POST"
                                        action="{{ route('notification_delete', ['id' => $notification->notification_id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-delete"
                                            onclick="return confirm('Are you sure you want to delete this notification?')">
                                            <svg width="19" height="24" viewBox="0 0 19 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M1.54386 21.7076C1.54386 22.4962 2.70175 23.5789 3.47368 23.5789H15.0526C15.8246 23.5789 16.9825 22.4962 16.9825 21.7076V6.73684H1.54386V21.7076ZM18.5263 2.46737H14.4094L12.3509 0H6.17544L4.11696 2.46737H0V4.93474H18.5263V2.46737Z"
                                                    fill="white" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($notification->type === 'New Coordinator')
                        <div class="notification-card new-coodinator">
                            <div class="title-description">
                                <h3>New Project Coordinator!</h3>
                                <p><b>{{ $notification->project_name }}</b> got just now a new project coordinator.</p>
                            </div>
                            <div class="time-actions">
                                <p class="time"><b>{{ $notification->date }}</b></p>
                                <div class="actions">
                                    <form method="POST"
                                        action="{{ route('notification_delete', ['id' => $notification->notification_id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-delete"
                                            onclick="return confirm('Are you sure you want to delete this notification?')">
                                            <svg width="19" height="24" viewBox="0 0 19 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M1.54386 21.7076C1.54386 22.4962 2.70175 23.5789 3.47368 23.5789H15.0526C15.8246 23.5789 16.9825 22.4962 16.9825 21.7076V6.73684H1.54386V21.7076ZM18.5263 2.46737H14.4094L12.3509 0H6.17544L4.11696 2.46737H0V4.93474H18.5263V2.46737Z"
                                                    fill="white" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($notification->type === 'Project Invite')
                        <div class="notification-card project-Invitation">
                            <div class="title-description">
                                <h3>Project Invitation!</h3>
                                <p>You've been invited to join the <b>{{ $notification->project_name }}</b> project.</p>
                            </div>
                            <div class="time-actions">
                                <p class="time"><b>{{ $notification->date }}</b></p>
                                <div class="actions">
                                    <form method="POST"
                                        action="{{ route('acceptInvite', ['id' => $notification->notification_id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-accept"
                                            onclick="return confirm('Are you sure you want to join that project?')">
                                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5.3335 16.8148L11.8976 23.3333L26.6668 8.66666" stroke="white"
                                                    stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </form>
                                    <form method="POST"
                                        action="{{ route('refuseInvite', ['id' => $notification->notification_id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-remove"
                                            onclick="return confirm('Are you sure you want to decline the project invite?')">
                                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M18.8 16L24.3 10.5C25.1 9.7 25.1 8.5 24.3 7.7C24 7.3 23.5 7 23 7C22.5 7 22 7.2 21.6 7.6L16 13.2L10.5 7.7C9.7 6.9 8.4 6.9 7.7 7.7C7.3 8 7 8.5 7 9.1C7 9.7 7.2 10.1 7.6 10.5L13.1 16L7.6 21.5C7.3 21.9 7 22.4 7 23C7 23.5 7.2 24 7.6 24.4C8 24.8 8.5 25 9 25C9.5 25 10 24.8 10.4 24.4L15.9 18.9L21.4 24.4C22.2 25.2 23.5 25.2 24.2 24.4C25 23.6 25 22.3 24.2 21.6L18.8 16Z"
                                                    fill="white" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                @if (count($notifications) === 0)
                    <h2 class="no-notifications">Currently you have no notifications.</h2>
                @endif
            </section>
        </section>
    </div>
@endsection
