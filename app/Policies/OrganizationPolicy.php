<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Organization;

class OrganizationPolicy{
    public function wasInvited(User $user, Organization $organization){
        return $organization->invitedUsers->contains($user);
    }
}
