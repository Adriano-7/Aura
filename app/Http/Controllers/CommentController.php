<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Event;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class CommentController extends Controller{
    public function index(Request $request) {
        if (!$request->has('eventId')) {
            return response()->json([
                'message' => 'Event id is required'
            ], 400);
        }
        
        $event_id = $request->query('eventId');
        if (!is_numeric($event_id)) {
            return response()->json([
                'message' => 'Event id must be an integer'
            ], 400);
        }

        if (!Event::find($event_id)) {
            return response()->json([
                'message' => 'Event not found'
            ], 404);
        }

        $comments = Comment::event_comments($event_id);
        return response()->json([
            'eventId' => $event_id,
            'comments' => $comments->toArray()
        ]);
    }

    public function show(int $id) {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found'
            ], 404);
        }

        return response()->json([
            'comment' => $comment
        ]);
    }

    public function destroy(int $id) {
        $comment = Comment::findOrFail($id);
        $this->authorize('delete', $comment);
        $comment->delete();

        return redirect(URL::previous() . '#comments')->with('success', 'Comment added successfully');  
    }


    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required',
            'file' => 'file|mimes:jpg,jpeg,png,bmp,gif,svg,pdf|max:2048'
        ]);

        $event = Event::findOrFail($request->event_id);
        $this->authorize('store', [Comment::class, $event, $request->user()]);

        $comment = new Comment;
        $comment->user_id = Auth::user()->id;
        $comment->text = $request->text;
        $comment->event_id = $request->event_id;
        $comment->save();

        if ($request->hasFile('file')) {
            $fileRequest = $request->file('file');
            $file = new File();
            $file->file_name = time() . "-" . $fileRequest->getClientOriginalName();
            $file->comment_id = $comment->id;
            $file->save(); // Save the file to generate an ID

            $comment->file_id = $file->id; // Set the file_id on the comment
            // update comment in table
            $comment->save();

            /* store the file in app/public/uploads */
            $fileRequest->storeAs('public/uploads', $file->file_name);
        }

        return redirect(URL::previous() . '#comments')->with('success', 'Comment added successfully');
    }
}
