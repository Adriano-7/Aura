<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Notification;
use App\Models\Organization;

class NotificationsController extends Controller{
    public function show(): View{
        $this->authorize('view', Notification::class);

        return view('pages.notifications', [
            'user' => Auth::user(),
            'notifications' => Notification::where('receiver_id', Auth::user()->id)->orderBy('date', 'desc')->get()
        ]);
    }

    public function markAsSeen(Request $request){
        if(!Auth::check()){
            abort(403, 'You must be logged in to mark a notification as seen');
        }

        $notification = Notification::find($request->id);
        $this->authorize('markAsSeen', $notification);

        $notification->seen = true;
        $notification->save();

        return redirect($notification->getLink());
    }

    //TODO: Transform the methods below into api endpoints

    public function delete(Request $request){
        if(!Auth::check()){
            abort(403, 'You must be logged in to delete a notification');
        }

        $notification = Notification::findOrFail($request->id);
        $this->authorize('delete', $notification);

        $notification->delete();
        
        return redirect()->route('notifications');
    }

    public function acceptInvitation(Request $request){
        if(!Auth::check()){
            abort(403, 'You must be logged in to accept an invitation');
        }

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
    
    public function approveOrganization(int $organizationId) {
        if(!Auth::check()){
            abort(403, 'You must be logged in to approve an organization');
        }

        $organization = Organization::findOrFail($organizationId);
        $notifications = $organization->organizationRegistrationRequests;

        foreach ($notifications as $notification) {
            $this->authorize('approve_org', $notification);
            $notification->delete();
        }
    
        $organization->approved = true;
        $organization->save();
    
        return redirect()->back()->with('status', 'Organização aprovada com sucesso!');
    }

}
