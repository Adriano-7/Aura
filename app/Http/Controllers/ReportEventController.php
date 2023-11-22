<?php

namespace App\Http\Controllers;

use App\Models\ReportEvent;
use Illuminate\Http\Request;

class ReportEventController extends Controller
{
    public function index() {
        $reports = ReportEvent::all();

        return response()->json($reports);
    }

    public function destroy(int $id) {
        $report = ReportEvent::find($id);

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
        $report = ReportEvent::find($id);

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
