<?php


namespace App\Http\Controllers;

use App\Models\ReportEvent;
use Illuminate\Http\Request;

class ReportEventController extends Controller
{
    public function index() {
        $this->authorize('viewAny', ReportEvent::class);

        $reports = ReportEvent::all();

        return response()->json($reports);
    }

    public function markAsResolved(int $id) {
        $this->authorize('markAsResolved', ReportEvent::class);

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


