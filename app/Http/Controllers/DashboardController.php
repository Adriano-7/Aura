<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

 use App\Models; 


class DashboardController extends Controller{

    public function showReports(): View{
        return view('pages.dashboardReports', [
            'user' => Auth::user(),
            'reportEvents' => Models\ReportEvent::all(),
            'reportComments' => Models\ReportComment::all(),
        ]);
    }

    public function showMembers(): View{
        return view('pages.dashboardMembers', [
            'user' => Auth::user(),
            'members' => Models\User::all(),
        ]);
    }

    public function showOrganizations(): View{
        return view('pages.dashboardOrganizations', [
            'user' => Auth::user(),
            'organizations' => Models\Organization::all(),
            'organizationRequests' => Models\Notification::where('type', 'organization_registration_request')->get(),
        ]);
    }
}