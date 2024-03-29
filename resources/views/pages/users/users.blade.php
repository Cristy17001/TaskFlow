@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/manageUsers.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}">
@endsection

@section('javascript')
    <script src="{{ asset('js/navigation.js') }}"></script>
    <script src="{{ asset('js/add_user.js') }}"></script>
@section('title', 'Users')

@section('content')
    <x-navigation activePage="admin" />
    <div class="wrapper">
        <section class="content">
            <h1>Users List</h1>
            <div class="top-of-table">
                <form class="search-form" action="{{ route('list_users') }}" method="GET">
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
                <button id="add-user-btn">
                    <svg width="20" height="22" viewBox="0 0 20 22" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M10 20.3243C5.1675 20.3243 1.25 16.1771 1.25 11.0641C1.25 5.95118 5.1675 1.80393 10 1.80393C14.8325 1.80393 18.75 5.95118 18.75 11.0641C18.75 16.1771 14.8325 20.3243 10 20.3243ZM10 0.481049C4.47688 0.481049 0 5.21698 0 11.0641C0 16.9113 4.47688 21.6472 10 21.6472C15.5231 21.6472 20 16.9113 20 11.0641C20 5.21698 15.5231 0.481049 10 0.481049ZM13.75 10.4027H10.625V7.09548C10.625 6.73169 10.3456 6.43404 10 6.43404C9.65438 6.43404 9.375 6.73169 9.375 7.09548V10.4027H6.25C5.90438 10.4027 5.625 10.7003 5.625 11.0641C5.625 11.4279 5.90438 11.7256 6.25 11.7256H9.375V15.0328C9.375 15.3966 9.65438 15.6942 10 15.6942C10.3456 15.6942 10.625 15.3966 10.625 15.0328V11.7256H13.75C14.0956 11.7256 14.375 11.4279 14.375 11.0641C14.375 10.7003 14.0956 10.4027 13.75 10.4027Z"
                            fill="white" />
                    </svg>
                    Add User</p>
                </button>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Admin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $i => $user)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="team-image-container">
                                        <div class="team-image circle" data-tooltip="{{ $user->name }}">
                                            <img alt="team-member" src="{{ $user->getProfileImage() }}" />
                                        </div>
                                    </div>
                                    <span>{{ $user->name }}</span>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->administrator ? 'Yes' : 'No' }}</td>
                            <td class="actions">
                                @if (auth()->user()->isAdmin())
                                    <a href="" class="btn-edit" data-index = "{{ $i }}">Edit</a>
                                    <dialog class="edit-info-modal" data-index = "{{ $i }}">
                                        <div>
                                            <h2>Edit User:</h2>
                                            <form method="POST"
                                                action="{{ route('admin_update_user', ['id' => $user->user_id]) }}"
                                                class="edit_user">
                                                @csrf
                                                @method('PUT')
                                                <label for="name">Name:</label>
                                                <input type="text" name="name" value="{{ $user->name }}">
                                                <label for="username">Username:</label>
                                                <input type="text" name="username" value="{{ $user->username }}">
                                                <label for="email">Email:</label>
                                                <input type="email" name="email" value="{{ $user->email }}">
                                                <input type="submit" value="Update info">
                                            </form>
                                        </div>
                                    </dialog>
                                @endif
                                @if (auth()->user()->isAdmin())
                                    <form method="POST"
                                        action="{{ route('delete_user', ['id' => $user->user_id, 'admin' => true]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-user-btn"
                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                            <svg width="19" height="24" viewBox="0 0 19 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M1.54386 21.7076C1.54386 22.4962 2.70175 23.5789 3.47368 23.5789H15.0526C15.8246 23.5789 16.9825 22.4962 16.9825 21.7076V6.73684H1.54386V21.7076ZM18.5263 2.46737H14.4094L12.3509 0H6.17544L4.11696 2.46737H0V4.93474H18.5263V2.46737Z"
                                                    fill="white" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
        <div>
            {{ $users->onEachSide(1)->links() }}
        </div>
    </div>
    <dialog id="modal-add-user">
        <form id="add-user-form" action="{{ route('store_user') }}" method="POST">
            @csrf
            <h2>Add User:</h2>
            <label for="name">Name:</label>
            <input id="name" name="name" type="text" placeholder="Enter Name">
            <label for="email">User Email:</label>
            <input id="email" name="email" type="text" placeholder="Enter Email">
            <label for="username">Username:</label>
            <input id="username" name="username" type="text" placeholder="Enter Username">
            <label for="password">Password:</label>
            <input id="password" name="password" type="password" placeholder="Enter Password">
            <label for="administrator">Administrator</label>
            <select id="administrator" name="administrator">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
            <input type="submit" value="Add User">
        </form>
    </dialog>
@endsection
