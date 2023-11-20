<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Organization;

class OrganizationPolicy{

public function join(User $user, Organization $organization){
    $invitedUsers = $organization->invitedUsers()->get();


    return true;
}
}
