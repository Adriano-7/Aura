<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Organization;

class OrganizationPolicy{
    public function wasInvited(User $user, Organization $organization){
        return $organization->organizationInvitations()->where('receiver_id', $user->id)->exists();
    }

    public function delete(User $user, Organization $organization){
        return $user->is_admin;
    }
    public function invite_user(User $user, Organization $organization): bool{
        return $user->is_admin || $user->isOrganizer($organization);
    }

    public function eliminate_member(User $user, Organization $organization): bool{
        return $user->is_admin || $user->isOrganizer($organization);
    }

    public function approve(User $user, Organization $organization): bool{
        return $user->is_admin;
    }
}
