<dialog class="modal-task-card" itemid="{{ $task->task_id }}">
    <div>
        <section class="description-comments">
            <h2>{{ $task->name }}</h2>
            <h3 class="description">Description:</h3>
            <p>{{ $task->description }}</p>
            <h3>Labels:</h3>
            <div class="container-labels">
                @foreach ($task->labels as $label)
                    <span>{{ $label->name }}</span>
                @endforeach
            </div>
            <h3>Assignee's:</h3>
            <div class="container-team">
                @foreach ($task->assignees as $assignee)
                    <div class="team-member">
                        <div class="team-image-container">
                            <div class="team-image circle" data-tooltip="{{ $assignee->username }}">
                                <img alt="team-member" src="{{ $assignee->getProfileImage() }}" />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <h3>Comments:</h3>
            <div class="block">
                @if (!$project->archived)
                    <form class="writing" method="POST" data-project-id={{ $project->project_id }}
                        data-task-id="{{ $task->task_id }}">
                        @csrf
                        <textarea name="comment" class="textarea" autofocus spellcheck="false" placeholder="Enter a comment..."></textarea>
                        <div class="footer">
                            <div class="group-button">
                                <button class="btn primary btn-comment">Send</button>
                            </div>
                        </div>
                    </form>
                @endif
                <div class="container-comments">
                    @foreach ($task->comments as $comment)
                        <div class="comment">
                            <div class="user-banner">
                                <div class="user">
                                    <div class="team-image circle">
                                        <img src="{{ $comment->user->getProfileImage() }}" />
                                    </div>
                                    <h5>{{ $comment->user->username }}</h5>
                                </div>

                                <button class="btn dropdown"><i class="ri-more-line"></i></button>
                            </div>
                            <div class="content">
                                <p>
                                    {{ $comment->comment }}
                                </p>
                            </div>
                            <div class="footer">
                                <span class="is-mute">{{ $comment->date }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <section class="actions-info">
            @if (!$project->archived)
                <div class="actions">
                    <h3>Actions</h3>
                    <button class="edit">
                        <img width="32" height="32" src="{{ asset('edit.png') }}">
                        Edit
                    </button>
                </div>
            @endif
            <div class="due-date">
                <h3>Due Date</h3>
                <p>{{ $task->due_date }}</p>
            </div>
            <div class="priority">
                <h3>Priority</h3>
                <p data-priority-opened="{{ $task->priority }}">{{ $task->priority }}</p>
            </div>
            <div class="status">
                <h3>Status</h3>
                <p data-status-opened="{{ $task->status }}">{{ $task->status }}</p>
            </div>
        </section>
    </div>
</dialog>

@if (!$project->archived)
    <dialog class="modal-task-card-edit" itemid="{{ $task->task_id }}">
        <form class="edit-modal-form" method="POST">
            @csrf
            @method('PUT')
            <section class="description-comments">
                <textarea name="title" id="title" rows="1">{{ $task->name }}</textarea>
                <h3 class="description">Description:</h3>
                <textarea id="description" name="description" rows="4">{{ $task->description }}</textarea>
                <h3>Labels:</h3>
                <div class="container-labels">
                    @foreach ($task->labels as $label)
                        <span data-label-name="{{ $label->name }}">{{ $label->name }}<span
                                class="remove-label">X</span></span>
                    @endforeach
                </div>
                <label for="add-labels" class="add-label">
                    <input type="text" id="add-labels" placeholder="Enter label..." />
                    <button class="add-label-button">
                        <svg width="27" height="26" viewBox="0 0 27 26" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.7539 20.2669V5.63623" stroke="white" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M6.08887 12.9517H21.419" stroke="white" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                </label>
                <h3>Assignee's:</h3>
                <div class="avatars-container">
                    @foreach ($task->assignees as $assignee)
                        <div class="member-container">
                            <p data-user-id={{ $assignee->user_id }}>{{ $assignee->username }}</p>
                            <button class="remove-member-from-task">
                                <svg width="10" height="12" viewBox="0 0 10 12" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M1.41131 10.6737C1.41131 11.0534 1.96881 11.5747 2.34048 11.5747H7.91553C8.2872 11.5747 8.84471 11.0534 8.84471 10.6737V3.46559H1.41131V10.6737ZM9.58805 1.40992H7.60581L6.61469 0.221924H3.64133L2.65021 1.40992H0.667969V2.59791H9.58805V1.40992Z"
                                        fill="white"></path>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
                <div class="assigne-user">
                    <select>
                        @foreach ($team as $member)
                            <option value="{{ $member->username }}" data-user-id={{ $member->user_id }}>
                                {{ $member->username }}</option>
                        @endforeach
                    </select>
                </div>
            </section>
            <section class="actions-info">
                <div class="actions">
                    <h3>Actions</h3>
                    <input data-task-id={{ $task->task_id }} data-project-id={{ $project->project_id }} type="submit"
                        value="Save" />
                </div>
                <label for="due-date"> Due Date </label>
                <input type="date" id="due-date" value="{{ $task->due_date }}" />
                <label for="priority"> Priority </label>
                <select id="priority">
                    <option value="High" {{ $task->priority === 'High' ? 'selected' : '' }}>High</option>
                    <option value="Medium" {{ $task->priority === 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="Low" {{ $task->priority === 'Low' ? 'selected' : '' }}>Low</option>
                </select>
                <label for="status"> Status </label>
                <select id="status" name="status">
                    <option value="Open" {{ $task->status === 'Open' ? 'selected' : '' }}>Open</option>
                    <option value="Doing" {{ $task->status === 'Doing' ? 'selected' : '' }}>Doing</option>
                    <option value="Review" {{ $task->status === 'Review' ? 'selected' : '' }}>Review</option>
                    <option value="Done" {{ $task->status === 'Done' ? 'selected' : '' }}>Done</option>
                </select>

            </section>
        </form>
    </dialog>

@endif
