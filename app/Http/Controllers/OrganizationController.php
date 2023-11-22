<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

 use App\Models\Organization; 


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
}
