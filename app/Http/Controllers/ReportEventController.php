<?php


namespace App\Http\Controllers;

use App\Models\ReportEvent;
use App\Models\Event;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

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

    public function report(Request $request, $eventId){
        $validReasons = ['suspect_fraud', 'inappropriate_content', 'incorrect_information'];
        $reason = $request->input('reason');

        if (!in_array($reason, $validReasons)) {
            return response()->json([
                'message' => 'Invalid reason'
            ], 400);
        }

        $event = Event::find($eventId);
        if (!$event) {
            return response()->json([
                'message' => 'Event not found'
            ], 404);
        }

        $report = new ReportEvent();
        $report->event_id = $eventId;
        $report->reason = $reason;
        $report->save();

        return response()->json([
            'message' => 'Comment reported'
        ]);
    }
}


