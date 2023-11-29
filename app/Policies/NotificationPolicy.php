<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationPolicy{

    public function view(User $user): bool{
        return Auth::check();
    }

    public function delete(User $user, Notification $notification): bool{
        return Auth::check() && $user->id === $notification->receiver_id;     
    }

    public function markAsSeen(User $user, Notification $notification): bool{
        return Auth::check() && $user->id === $notification->receiver_id;
    }

    public function approve_org(User $user, Notification $notification): bool{
        return ($user->is_admin && $notification->type === 'organization_registration_request');
    }
}
