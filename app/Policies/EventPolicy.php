<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;

class EventPolicy{
    public function join(User $user, Event $event){
        return !$user->isAdmin();
    }
}
