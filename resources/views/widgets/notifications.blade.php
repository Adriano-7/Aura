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
            <div class="col-md-8" onclick="window.location.href='{{ route('notification.markAsSeen', ['id' => $notification->id]) }}'">
                <p class="notification-content">
                    {{ $notification->getContent() }}
                </p>
            </div>
            <div class="col-md-1 notification-buttons">
                <div class="row">
                    @if ($notification->type == 'event_invitation' || $notification->type == 'organization_invitation')
                        <div class="col-md-1">
                            <form action="{{ route('notification.acceptInvitation', ['id' => $notification->id]) }}" method="POST" id="acceptForm{{ $notification->id }}">
                                @csrf
                                @method('PATCH')
                                <img src="{{ asset('storage/check-icon.svg') }}" onclick="document.getElementById('acceptForm{{ $notification->id }}').submit()" style="cursor: pointer;">
                            </form>
                        </div>
                    @endif

                    <div class="col-md-1">
                        <form action="{{ route('notification.delete', ['id' => $notification->id]) }}" method="POST" id="deleteForm{{ $notification->id }}">
                            @csrf
                            @method('DELETE')
                            <img src="{{ asset('storage/close-icon.svg') }}" onclick="document.getElementById('deleteForm{{ $notification->id }}').submit()" style="cursor: pointer;">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
