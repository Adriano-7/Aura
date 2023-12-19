<?php

namespace App\Http\Controllers;
use Illuminate\Auth\Access\AuthorizationException;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

use App\Helpers\ColorHelper;


class UserController extends Controller{
    public function destroy(int $id) {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        try {
            $this->authorize('delete', $user);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'User not authorized to delete this user'], 403);
        }
        $user->delete();

        return response()->json([
            'message' => 'User deleted'
        ], 200);
    }

    public function show(string $username) {
        $userProfile = User::where('username', $username)->first();

        if (!$userProfile) {
            abort(404, 'Utilizador não encontrado.');
        }
    
        $color1_increment = -120;
        $color2_increment = 50;
        return view('pages.profile', [
            'userProfile' => $userProfile,
            'user' => Auth::user(),
            'color1' => ColorHelper::adjustBrightness($userProfile->background_color, $color1_increment),
            'color2' => ColorHelper::adjustBrightness($userProfile->background_color, $color2_increment),
            'organizations' =>  $userProfile->organizations,
            'events' => $userProfile->eventsWhichParticipates(),
        ]);
    }
}
