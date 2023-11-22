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

    public function invite_user_org(User $user, Organization $organization): bool{
        return $user->isAdmin() || $user->isOrganizer($organization);
    }
}
