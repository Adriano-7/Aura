<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use App\Models\Event;

class EventPolicy{
    public function join(User $user, Event $event){
        return !$user->isAdmin();
    }

    public function leave(User $user, Event $event){
        return !$user->isAdmin();
    }
    public function delete(User $user, Event $event){
        $organisations = Organization::findOrFail($event->organization_id);
        $org_users = $organisations->organizers()->get();

        return ($user->isAdmin() || $org_users->contains($user));
    }

    public function update(User $user, Event $event){
        $organisations = Organization::findOrFail($event->organization_id);
        $org_users = $organisations->organizers()->get();

        return ($user->isAdmin() || $org_users->contains($user));
    }


    public function invite_user(User $user, Event $event){
        return !$user->isAdmin();
    }

    public function viewEditForm(User $user, Event $event){
        $organisations = Organization::findOrFail($event->organization_id);
        $org_users = $organisations->organizers()->get();

        return ($user->isAdmin() || $org_users->contains($user));
    }
}
