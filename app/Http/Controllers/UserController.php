<?php

namespace App\Http\Controllers;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

use App\Helpers\ColorHelper;
use Illuminate\Http\Request;


class UserController extends Controller{
    public function destroy(int $id) {
        if(!is_numeric($id)){
            return response()->json(['error' => 'User id must be an integer'], 400);
        }
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

        $color1_increment = -122;
        $color2_increment = 55;

        $events = $userProfile->eventsWhichParticipates();

        if(Auth::check()){
            $user = Auth::user();
            $events = $events->filter(function ($event) use ($user) {
                return Gate::forUser($user)->allows('show', $event);
            });
        } else {
            $events = $events->filter(function ($event) {
                return $event->is_public;
            });
        }

        return view('pages.profile', [
            'userProfile' => $userProfile,
            'user' => Auth::user(),
            'color1' => ColorHelper::adjustBrightness($userProfile->background_color, $color1_increment),
            'color2' => ColorHelper::adjustBrightness($userProfile->background_color, $color2_increment),
            'organizations' =>  $userProfile->organizations,
            'events' => $events,
        ]);
    }
    public function update(Request $request, int $id) {
        if(!is_numeric($id)){
            return redirect()->back()->withErrors('User id must be an integer');
        }
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->withErrors('User not found');
        }

        $request->validate([
            'name' => 'required|string|max:50',
            'username' => ['required', 'string', 'max:20', 'unique:users,username,' . $user->id, 'regex:/^[a-zA-Z][a-zA-Z0-9_.-]*$/'],
            'email' => 'required|email|max:250|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        
        try {
            $this->authorize('update', $user);
        } catch (AuthorizationException $e) {
            return redirect()->back()->withErrors('You are not authorized to update this user.');
        }
        $user->update($request->all());

        if($request->has('photo')) {
            $fileRequest = $request->file('photo');
            $filename = time() . "-" . $fileRequest->getClientOriginalName();
            $fileRequest->move(public_path('assets/profile'), $filename);
            $user->photo = $filename;
            $user->save();
        }

        return redirect()->route('user', ['username' => $user->username]);
    }
}
