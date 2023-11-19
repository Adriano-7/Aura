<div class="container">
    @foreach ($notifications as $notification)
        <div class="row notification">
            <div class="col-md-2 notification-profile">
                <img class="rounded-circle notification-profile-img" src="{{ asset($notification->getImage()) }}"
                    alt="Profile Image">
                <div class="notification-profile-info">
                    <p class="notification-name">{{ $notification->getSenderName() }}</p>
                    <p class="notification-date">{{ $notification->getNiceDate() }}</p>
                </div>
            </div>
            <div class="col-md-8">
                <p class="notification-content">
                    {{ $notification->getContent() }}
                </p>
            </div>
            <div class="col-md-1 notification-buttons">
                <a href="#"><img src="{{ asset('storage/Close_round.svg') }}" class="notification-close"></a>
            </div>
        </div>
    @endforeach
</div>
