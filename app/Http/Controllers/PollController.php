<?php

namespace App\Http\Controllers;


use App\Models\Event;
use App\Models\File;
use App\Models\Poll;
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

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'options' => 'required',
            'eventId' => 'required'
        ]);

        $event_id = Event::findOrFail($request->event_id);

        try{
            $this->authorize('store', [Poll::class, $event_id, $request->user()]);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'User not authorized to comment on this event'], 403);
        }

        $poll = Poll::create([
            'event_id' => $event_id,
            'question' => $request->input('question'),
        ]);

        $options = $request->input('options');
        foreach ($options as $option) {
            $poll->options()->create([
                'text' => $option
            ]);
        }
        return response()->json(['message' => 'Poll created successfully']);
    }
}




?>