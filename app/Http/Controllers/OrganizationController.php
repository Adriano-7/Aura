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
        $this->authorize('join', $organization);
        $organization->organizers()->attach(Auth::user()->id);
        return redirect()->route('organization', ['id' => $id]);
    }
}