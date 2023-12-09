<?php

namespace App\Http\Controllers;

use App\Models\ReportComment;
use Illuminate\Auth\Access\AuthorizationException;

class ReportCommentController extends Controller
{
    public function index() {
        try {
            $this->authorize('viewAny', ReportComment::class);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'User not authorized to view reports'], 403);
        }

        $reports = ReportComment::all();

        return response()->json($reports);
    }

    public function destroy(int $id) {
        try {
            $this->authorize('delete', ReportComment::class);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'User not authorized to delete reports'], 403);
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
}
