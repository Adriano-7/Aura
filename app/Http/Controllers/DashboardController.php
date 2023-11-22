<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Organization;
use App\Models\ReportComment;
use App\Models\ReportEvent;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller{

    public function showReports(): View{
        return view('pages.dashboardReports', [
            'user' => Auth::user(),
            'reportEvents' => ReportEvent::where('resolved', false)->get(),
            'reportComments' => ReportComment::where('resolved', false)->get(),
        ]);
    }

    public function showMembers(): View{
        return view('pages.dashboardMembers', [
            'user' => Auth::user(),
            'members' => User::where('id', '!=', Auth::id())->get(),
        ]);
    }

    public function showOrganizations(): View {
        $userId = Auth::id();
    
        return view('pages.dashboardOrganizations', [
            'user' => Auth::user(),
            'organizations' => Organization::all(),
            'organizationRequests' => Notification::where([
                ['type', 'organization_registration_request'],
                ['receiver_id', $userId],
            ])->get(),
        ]);
    }
    
}
