<?php

namespace App\Http\Controllers;

use App\Models\ReportComment;
use App\Models\Comment;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class ReportCommentController extends Controller
{
    public function index() {
        try {
            $this->authorize('viewAny', ReportComment::class);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'User not authorized to view reports'], 403);
        }

        $reports = ReportComment::orderBy('created_at', 'desc')->get();

        return response()->json($reports);
    }

    public function destroy(int $id) {
        try {
            $this->authorize('delete', ReportComment::class);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'User not authorized to delete reports'], 403);
        }

        if(!is_numeric($id)){
            return response()->json(['error' => 'Report id must be an integer'], 400);
        }
        $report = ReportComment::find($id);

        if (!$report) {
            return response()->json([
                'message' => 'Report not found'
            ], 404);
        }

        $report->delete();

        return response()->json([
            'message' => 'Report deleted'
        ]);
    }

    public function markAsResolved(int $id) {
        try {
            $this->authorize('markAsResolved', ReportComment::class);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'User not authorized to mark reports as resolved'], 403);
        }

        if(!is_numeric($id)){
            return response()->json(['error' => 'Report id must be an integer'], 400);
        }
        $report = ReportComment::find($id);

        if (!$report) {
            return response()->json([
                'message' => 'Report not found'
            ], 404);
        }

        $report->resolved = true;
        $report->save();

        return response()->json([
            'message' => 'Report marked as resolved'
        ]);
    }

    public function report(Request $request, int $commentId){
        $validReasons = ['inappropriate_content', 'violence_threats', 'incorrect_information', 'harassment_bullying', 'commercial_spam'];
        $reason = $request->input('reason');

        if (!in_array($reason, $validReasons)) {
            return response()->json([
                'message' => 'Invalid reason'
            ], 400);
        }

        if(!is_numeric($commentId)){
            return response()->json(['error' => 'Comment id must be an integer'], 400);
        }
        $comment = Comment::find($commentId);
        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found'
            ], 404);
        }

        $report = new ReportComment();
        $report->comment_id = $comment->id;
        $report->reason = $reason;
        $report->save();

        return response()->json([
            'message' => 'Comment reported'
        ]);
    }
}