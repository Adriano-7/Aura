<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;

use App\Models\User;
use App\Models\Notification;
use App\Models\Organization;


class NotificationsController extends Controller{
    public function show(): View{
        if(!Auth::check()){
            abort(403, 'Utilizador não autenticado.');
        }

        return view('pages.notifications', [
            'user' => Auth::user(),
        ]);
    }

    public function markAsSeen(Request $request){
        if(!Auth::check()){
            abort(403, 'Utilizador não autenticado.');
        }

        $notification = Notification::find($request->id);
        $this->authorize('markAsSeen', $notification);

        $notification->seen = true;
        $notification->save();

        return redirect($notification->getLink());
    }

    public function delete(Request $request){
        $notification = Notification::find($request->id);
        if(!$notification){
            return response()->json(['message' => 'Notification not found'], 404);
        }

        try{
            $this->authorize('delete', $notification);
        }
        catch(AuthorizationException $e){
            return response()->json(['message' => 'User not authorized to delete this notification'], 403);
        }

        $notification->delete();
        
        return response()->json(['message' => 'Notification deleted successfully']);
    }
}
