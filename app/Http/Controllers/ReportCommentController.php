<?php

namespace App\Http\Controllers;

use App\Models\ReportComment;
use Illuminate\Http\Request;

class ReportCommentController extends Controller
{
    public function index() {
        $this->authorize('viewAny', ReportComment::class);

        $reports = ReportComment::all();

        return response()->json($reports);
    }

    public function destroy(int $id) {
        $this->authorize('delete', ReportComment::class);

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
        $this->authorize('markAsResolved', ReportComment::class);

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
