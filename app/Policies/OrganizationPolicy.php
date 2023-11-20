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
}
