<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;

 use App\Models\Organization; 
 use App\Models\Notification;
 use App\Models\User;


class OrganizationController extends Controller{
    public function show($id): View{
        $organization = Organization::find($id);
        if(!$organization){
            abort(404, 'Organização não encontrada.');
        }

        return view('pages.organization', [
            'user' => Auth::user(),
            'organization' => $organization,
        ]);
    }

    public function joinOrganization($id){
        $organization = Organization::find($id);
        $this->authorize('wasInvited', $organization);
        $organization->organizers()->attach(Auth::user()->id);

        Auth::user()->notifications()->where('type', 'organization_invitation')->where('organization_id', $id)->delete();

        return redirect()->route('organization.show', ['id' => $id]);
    }

    public function deleteOrg(int $id) {
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
        $organization = Organization::findOrFail($request->organization_id);
        $this->authorize('invite_user', $organization);

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

    public function approve(int $organizationId) {
        if(!Auth::check()){
            return response()->json(['error' => 'User not logged in'], 403);
        }

        $organization = Organization::find($organizationId);
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
