<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class CommentController extends Controller
{
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

        return response()->json([
            'message' => 'Comment deleted successfully'
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required',
            'file' => 'file|mimes:jpg,jpeg,png,bmp,gif,svg,pdf|max:2048'
        ]);

        $comment = new Comment;
        $comment->author_id = Auth::user()->id;
        $comment->text = $request->text;
        $comment->event_id = $request->event_id;

        /*
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $comment->file = $filename;
        }
        */
        
        $comment->save();
        return redirect(URL::previous() . '#comments')->with('success', 'Comment added successfully');    }
}
