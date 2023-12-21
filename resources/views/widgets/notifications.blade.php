<div class="container" id="notifications-container">
    @foreach ($notifications as $notification)
        <div class="row notification" id="notification-{{ $notification->id }}">
            <div class="col-md-3 notification-profile d-flex align-items-center" onclick="window.location.href='{{ route('user', ['username' => $notification->userEmitter->username]) }}'" style="cursor:pointer">
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
            <div class="col-md-8 d-flex align-items-center">
                <form method="POST" action="{{ route('notification.markAsSeen', ['id' => $notification->id]) }}">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="notification-content">
                        {{ $notification->getContent() }}
                    </button>
                </form>
            </div>
            <div class="col-md-1 notification-buttons d-flex align-items-center">
                <div class="row">
                    @if ($notification->type == 'event_invitation')
                        <div class="col-md-1">
                            <form method="POST" action="{{ route('event.join', $notification->event->id) }}">
                                @csrf
                                <img src="{{ asset('assets/check-icon.svg') }}" onclick="submit()"
                                    style="cursor: pointer;" alt="Aceitar convite para evento">
                            </form>
                        </div>
                    @elseif ($notification->type == 'organization_invitation')
                        <div class="col-md-1">
                            <form method="POST"
                                action="{{ route('organization.join', $notification->organization->id) }}">
                                @csrf
                                <img src="{{ asset('assets/check-icon.svg') }}" onclick="submit()"
                                    style="cursor: pointer;" alt="Aceitar convite para organização">
                            </form>
                        </div>
                    @endif

                    <div class="col-md-1">
                        <img src="{{ asset('assets/close-icon.svg') }}"
                            onclick="deleteNotification('{{ $notification->id }}')" style="cursor: pointer;" alt="Apagar notificação">
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
