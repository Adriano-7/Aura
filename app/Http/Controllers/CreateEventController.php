<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Organization;
use App\Models\User;


class CreateEventController extends Controller{
    public function show(): View{

        $user = Auth::user();
        // Assuming you have a 'organizations' many-to-many relationship in your User model
        $organizations = $user->userOrganizations()->get();

        return view('pages.createEvent', [
            'user' => Auth::user(),
            'organizations' => $organizations
        ]);
    }
}