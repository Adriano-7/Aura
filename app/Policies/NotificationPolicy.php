<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NotificationPolicy{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Notification $notification): bool{
        return $user->id === $notification->receiver_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Notification $notification): bool{
        return $user->id === $notification->receiver_id;
        
    }

    /**
     * Determine whether the user can approve the organization.
     */
    public function approve_org(User $user, Notification $notification): bool{
        return ($user->isAdmin() && $notification->type === 'organization_registration_request');
    }
}