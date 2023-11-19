<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Notification;

class NotificationsController extends Controller{
    public function show(): View{
        return view('pages.notifications', [
            'user' => Auth::user(),
            'notifications' => Notification::where('receiver_id', Auth::user()->id)->orderBy('date', 'desc')->get()
        ]);
    }
}