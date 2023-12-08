<?php

namespace App\Http\Controllers;
use Illuminate\Auth\Access\AuthorizationException;

use App\Models\User;

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
}
