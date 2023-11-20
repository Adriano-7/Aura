<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

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

        $comments = Comment::event_comments($event_id);
        return response()->json([
            'eventId' => $event_id,
            'comments' => $comments->toArray()
        ]);
    }

    public function destroy(int $id) {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully'
        ]);
    }
}
