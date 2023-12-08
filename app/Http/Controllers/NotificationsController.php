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
        if(!Auth::check()){
            abort(404);
        }

        return view('pages.notifications', [
            'user' => Auth::user(),
        ]);
    }

    public function markAsSeen(Request $request){
        if(!Auth::check()){
            abort(404);
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
            abort(404);
        }

        $notification = Notification::findOrFail($request->id);
        $this->authorize('delete', $notification);

        $notification->delete();
        
        return redirect()->route('notifications');
    }
    
    public function approveOrganization(int $organizationId) {
        if(!Auth::check()){
            abort(404);
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
