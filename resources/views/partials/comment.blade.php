<div class="comment">
    <div class="user-banner">
        <div class="user">
            <div class="team-image circle">
                <img src="{{ $user->getProfileImage() }}" />
            </div>
            <h5>{{ $comment['username'] }}</h5>
        </div>
        <button class="btn dropdown"><i class="ri-more-line"></i></button>
    </div>
    <div class="content">
        <p>
            {{ $comment['comment'] }}
        </p>
    </div>
    <div class="footer">
        <span class="is-mute">{{ $comment['date'] }}</span>
    </div>
</div>
