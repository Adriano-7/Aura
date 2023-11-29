<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ReportEvent;
use Illuminate\Support\Facades\Auth;

class ReportEventPolicy
{
    public function viewAny(): bool {
        return Auth::check() && Auth::user()->is_admin;
    }

    public function delete(): bool {
        return Auth::check() && Auth::user()->is_admin;
    }

    public function markAsResolved(): bool {
        return Auth::check() && Auth::user()->is_admin;
    }
}
