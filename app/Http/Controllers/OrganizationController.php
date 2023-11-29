<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

 use App\Models\Organization; 
 use App\Models\Notification;
 use App\Models\User;


class OrganizationController extends Controller{
    public function show($id): View{
        return view('pages.organization', [
            'user' => Auth::user(),
            'organization' => Organization::find($id)
        ]);
    }

    public function joinOrganization($id){
        $organization = Organization::find($id);
        $this->authorize('wasInvited', $organization);
        $organization->organizers()->attach(Auth::user()->id);
        return redirect()->route('notifications')->with('status', "Entraste com sucesso na organização {$organization->name}");
    }

    public function ApiDelete(int $id) {
        $org = Organization::find($id);

        if (!$org) {
            return response()->json([
                'message' => 'Organization not found'
            ], 404);
        }

        $this->authorize('delete', $org);
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
        $organization = Organization::findOrFail($request->organization_id);
        $this->authorize('eliminate_member', $organization);

        $user = User::findOrFail($request->user_id);
        $organization->organizers()->detach($user->id);

        return redirect()->back()->with('status', 'Membro eliminado com sucesso!');
    }

}
