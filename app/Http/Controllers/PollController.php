<?php

namespace App\Http\Controllers;


use App\Models\Event;
use App\Models\File;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Access\AuthorizationException;


class PollController extends Controller{

    public function show(int $id) {
        $poll = Poll::find($id);
        if (!$poll) {
            return response()->json(['message' => 'Poll not found'], 404);
        }
        return response()->json(['poll' => $poll]);
    }

    public function results(int $id) {
        
        // Find the poll by id
        $poll = Poll::findOrFail($id);
        // Get the options of the poll
        $options = $poll->options;

        // Initialize an array to store the results
        $results = [];

        // Loop through each option
        foreach ($options as $option) {
            
            // Count the votes for the option
            
            $voteCount = optional($option->votes())->count() ?? 0;   
            
            // Calculate the percentage of votes for the option
            $totalVotes = $poll->votes()->count();
            
            $percentage = $totalVotes > 0 ? ($voteCount / $totalVotes) * 100 : 0;            
            
            // Store the result
            $results[] = [
                'option_id' => $option->id,
                'text' => $option->text,
                'percentage' => round($percentage, 2)
            ];
        }

        // Return the results
        
        return response()->json($results);    
    }        


    public function vote(Request $request, int $id) {
        $poll = Poll::find($id);
        if (!$poll) {
            return response()->json(['message' => 'Poll not found'], 404);
        }
        try {
            $this->authorize('vote', $poll);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'User not authorized to vote on this poll'], 403);
        }
    

        PollVote::addVote(intval($request->option_id), Auth::user()->id);
        return response()->json(['success' => true]);

       
    }

    public function hasVoted (int $id) {
        $poll = Poll::find($id);
        if (!$poll) {
            return response()->json(['message' => 'Poll not found'], 404);
        }


        $hasVoted = $poll->hasUserVoted(Auth::user()->id);
        $optionVoted = $poll->optionUserVoted(Auth::user()->id);


        error_log('Vote: ' . json_encode($optionVoted));

        error_log('$hasVoted is: ' . ($hasVoted ? 'true' : 'false'));
        return response()->json(['hasVoted' => $hasVoted,
                                 'optionVoted' => $optionVoted->poll_option_id ?? null]);      
    }


    public function store(Request $request){
        error_log('Form data: ' . json_encode($request->all()));



        $request->validate([
            'eventId' => 'required|string',
            'mainQuestion' => 'required|string',
            'option1' => 'required|string',
            'option2' => 'required|string',
            'option3' => 'string',
            'option4' => 'string',
            'option5' => 'string',
            'option6' => 'string',
        ]);


        $event = Event::find(intval($request->eventId));
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $poll = Poll::create([
            'event_id' => intval($request->eventId),
            'question' => $request->mainQuestion,
        ]);

        try {
            $this->authorize('create', $poll);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'User not authorized to create a poll'], 403);
        }
        // Create a PollOption for each option in the request
        foreach ($request->all() as $key => $value) {
            error_log('Key: ' . $key . ', Value: ' . $value);
            if (Str::startsWith($key, 'option') && !empty($value)) {
                error_log('Creating option: ' . $value);
                PollOption::create([
                    'poll_id' => $poll->id,
                    'text' => $value,
                ]);
            }
        }

        $poll->save();


        return response()->json(['success' => true]);
      


}
}
?>