<div class="container" id="notifications-container">
    @foreach ($notifications as $notification)
        <div class="row notification">
            <div class="col-md-3 notification-profile">
                <img class="rounded-circle notification-profile-img" src="{{ asset($notification->getImage()) }}"
                    alt="Profile Image">
                <div class="notification-profile-info">
                    <p class="notification-name">
                        {{ $notification->getSenderName() }}
                        @if (!$notification->seen)
                            <span class="seen">•</span>
                        @endif
                    </p>
                    <p class="notification-date">{{ $notification->getNiceDate() }}</p>
                </div>
            </div>
            <div class="col-md-8" onclick="window.location.href='{{ $notification->getLink() }}'">
                <p class="notification-content">
                    {{ $notification->getContent() }}
                </p>
            </div>
            <div class="col-md-1 notification-buttons">
                <form method="POST" action="{{ route('notification.delete', ['id' => $notification->id]) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background-color: transparent; border: none;">
                        <img src="{{ asset('storage/Close_round.svg') }}" class="notification-close">
                    </button>
                </form>
            </div>
        </div>
    @endforeach
</div>
