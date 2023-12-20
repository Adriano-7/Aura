<?php

namespace App\Http\Controllers;


use App\Models\Event;
use App\Models\File;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        error_log('PollController@store');
        error_log(print_r($request->all(), true));

        return response()->json(['success' => true]);
      


}
}
?>