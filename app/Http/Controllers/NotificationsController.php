<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Notification;

class NotificationsController extends Controller{
    public function show(): View{
        return view('pages.notifications', [
            'user' => Auth::user(),
            'notifications' => Notification::where('receiver_id', Auth::user()->id)->orderBy('date', 'desc')->get()
        ]);
    }

    public function delete(Request $request){
        $notification = Notification::findOrFail($request->id);
        $this->authorize('delete', $notification);

        $notification->delete();
        
        return redirect()->route('notifications')->with('status', 'Notification deleted successfully!');
    }

    public function markAsSeen(Request $request){
        $notification = Notification::find($request->id);
        $this->authorize('view', $notification);

        $notification->seen = true;
        $notification->save();

        return redirect($notification->getLink());
    }

    public function acceptInvitation(Request $request){
        $notification = Notification::find($request->id);

        if($notification->type == 'event_invitation'){
            $this->authorize('join', $notification->event);

            $eventId = $notification->event->id;
            $notification->delete();

            return redirect()->route('event.join', ['id' => $eventId]);
        }
        
        else if($notification->type == 'organization_invitation'){
            $this->authorize('wasInvited', $notification->organization);

            $organizationId = $notification->organization->id;
            $notification->delete();

            return redirect()->route('organization.join', ['id' => $organizationId]);
        }

        abort(403, 'This notification is not an invitation');
    }
}