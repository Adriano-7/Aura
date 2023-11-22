<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Organization;

class OrganizationPolicy{
    public function wasInvited(User $user, Organization $organization){
        return !$user->isAdmin();

        /*
        TODO: fix this
        return $organization->invitedUsers->contains($user);
        */
    }

    public function invite_user(User $user, Organization $organization): bool{
        return $user->isAdmin() || $user->isOrganizer($organization);
    }

    public function eliminate_member(User $user, Organization $organization): bool{
        return $user->isAdmin() || $user->isOrganizer($organization);
    }
}
