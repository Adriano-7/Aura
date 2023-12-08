<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Event;
use App\Models\File;
use App\Models\VoteComment;
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
            $file->save();
            $comment->file_id = $file->id;
            $comment->save();
            $fileRequest->storeAs('public', $file->file_name);
            $fileRequest->move(public_path('uploads'), $file->fileName);
        }

        return redirect(URL::previous() . '#comments')->with('success', 'Comment added successfully');
    }

    public function addLike(int $commentId){
        $comment = Comment::findOrFail($commentId);
        $this->authorize('addVote', [VoteComment::class, $comment]);
        VoteComment::addVote($commentId, Auth::user()->id, true);
        return response()->json(['success' => true]);
    }

    public function addDislike(int $commentId){
        $comment = Comment::findOrFail($commentId);
        $this->authorize('addVote', [VoteComment::class, $comment]);
        VoteComment::addVote($commentId, Auth::user()->id, false);
        return response()->json(['success' => true]);
    }

    public function removeVote(int $commentId){
        $comment = Comment::findOrFail($commentId);
        $this->authorize('deleteVote', [VoteComment::class, $comment]);
        VoteComment::deleteVote($commentId, Auth::user()->id);
        return response()->json(['success'=> true]);
    }
}
