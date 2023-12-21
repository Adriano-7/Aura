<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Poll;
use App\Models\Event;
use Illuminate\Auth\Access\Response;
use App\Models\Organization;

class PollPolicy{
    //To vote in a poll, the user must be a participant of the event, not an organizer, not an admin, not have voted yet.
    public function vote(User $user, Poll $poll){
        $event = $poll->event;
        $hasVoted = $poll->votes()->where('user_id', $user->id)->exists();
        $isOrganizer = $user->organizerEvent($event);
        return $event->participants->contains($user) && !$user->admin && !$hasVoted && !$isOrganizer;
    }

    public function create(User $user, Poll $poll){
        $event = Event::find($poll->event_id);
        return $user->organizerEvent($event);
    }
}