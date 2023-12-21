<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;

 use App\Models\Organization; 
 use App\Models\Notification;
 use App\Models\User;
 use App\Models\Event;
    use Illuminate\Support\Facades\Gate;


class OrganizationController extends Controller{
    public function show($id): View{
        if (!is_numeric($id)) {
            abort(404, 'Organização não encontrada.');
        }
        $organization = Organization::find($id);
        if(!$organization){
            abort(404, 'Organização não encontrada.');
        }

        if(Auth::check()){
            $user = Auth::user();
            $events = Event::where('organization_id', $id)->get()->filter(function ($event) use ($user) {
                return Gate::forUser($user)->allows('show', $event);
            });
        }
        else{
            $events = Event::where('organization_id', $id)->where('is_public', true)->get();
        }

        return view('pages.organization', [
            'user' => Auth::user(),
            'organization' => $organization,
            'events' => $events,
        ]);
    }

    public function joinOrganization(int $id){
        if(!is_numeric($id)){
            return redirect()->back()->withErrors('Organization id must be an integer');
        }
        $organization = Organization::find($id);
        if(!$organization){
            return redirect()->back()->withErrors('Organization not found');
        }
        try {
            $this->authorize('wasInvited', $organization);
        } 
        catch (AuthorizationException $e) {
            return redirect()->back()->withErrors('You are not authorized to join this organization.');
        }
        $organization->organizers()->attach(Auth::user()->id);
        Auth::user()->notifications()->where('type', 'organization_invitation')->where('organization_id', $id)->delete();
        return redirect()->route('organization.show', ['id' => $id]);
    }

    public function deleteOrg(int $id) {
        if (!is_numeric($id)) {
            return response()->json(['error' => 'Organization id must be an integer'], 400);
        }
        $org = Organization::find($id);

        if (!$org) {
            return response()->json([
                'message' => 'Organization not found'
            ], 404);
        }

        try {
            $this->authorize('delete', $org);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'User not authorized to delete this organization'], 403);
        }

        $org->delete();

        return response()->json([
            'message' => 'Organization deleted'
        ], 200);
    }

    public function inviteUser(Request $request){
        $request->validate([
            'email' => 'required|email',
            'organization_id' => 'required|integer',
        ]);
        $organization = Organization::find($request->organization_id);
        if(!$organization){
            return redirect()->back()->withErrors('Organization not found');
        }

        try{
            $this->authorize('invite_user', $organization);
        } catch (AuthorizationException $e) {
            return redirect()->back()->withErrors('You are not authorized to invite users to this organization.');
        }

        $user = User::where('email', $request->email)->first();

        if($user == null || $user->id == Auth::user()->id || $user->isOrganizer($organization) || $user->is_admin){
            return redirect()->back()->with('status', 'Utilizador não encontrado!');
        }

        $notification = new Notification();
        $notification->receiver_id = $user->id;
        $notification->type = 'organization_invitation';
        $notification->organization_id = $organization->id;
        $notification->user_emitter_id = Auth::user()->id;
        $notification->date = now();
        $notification->save();

        return redirect()->back()->with('status', 'Utilizador convidado com sucesso!');
    }

    public function eliminateMember(Request $request){
        $request->validate([
            'organization_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);
        $organization = Organization::find($request->organization_id);
        if(!$organization){
            return response()->json(['error' => 'Organization not found'], 404);
        }

        try{
            $this->authorize('eliminate_member', $organization);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'User not authorized to eliminate this member'], 403);
        }

        $user = User::find($request->user_id);
        if(!$user){
            return response()->json(['error' => 'Member not found'], 404);
        }

        $organization->organizers()->detach($user->id);

        return response()->json(['message' => 'Member eliminated successfully']);
    }

    public function approve(int $id) {
        if(!Auth::check()){
            return response()->json(['error' => 'User not logged in'], 403);
        }

        if(!is_numeric($id)){
            return response()->json(['error' => 'Organization id must be an integer'], 400);
        }
        $organization = Organization::find($id);
        if (!$organization) {
            return response()->json(['error' => 'Organization not found'], 404);
        }

        try{
            $this->authorize('approve', $organization);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'User not authorized to approve this organization'], 403);
        }

        $notifications = $organization->organizationRegistrationRequests;
        foreach ($notifications as $notification) {
            $notification->delete();
        }
    
        $organization->approved = true;
        $organization->save();
    
        return response()->json(['message' => 'Organization approved successfully']);
    }
}
