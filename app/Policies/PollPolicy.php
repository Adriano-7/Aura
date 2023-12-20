<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Poll;
use App\Models\Organization;

class PollPolicy{

    public function vote(User $user, Poll $poll){
        $event = $poll->event;
        $hasVoted = $poll->votes()->where('user_id', $user->id)->exists();
        return $event->participants->contains($user) && !$user->admin && !$hasVoted;
    }
}