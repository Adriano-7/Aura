<?php


namespace App\Http\Controllers;

use App\Models\ReportEvent;
use Illuminate\Auth\Access\AuthorizationException;


class ReportEventController extends Controller
{
    public function index() {
        try{
            $this->authorize('viewAny', ReportEvent::class);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'User not authorized to view reports'], 403);
        }

        $reports = ReportEvent::all();

        return response()->json($reports);
    }

    public function markAsResolved(int $id) {
        try{
            $this->authorize('markAsResolved', ReportEvent::class);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'User not authorized to mark reports as resolved'], 403);
        }

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


