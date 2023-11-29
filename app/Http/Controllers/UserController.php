<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller{
    public function destroy(int $id) {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $this->authorize('delete', $user); //TODO: Deal with the 403 error
        $user->delete();

        return response()->json([
            'message' => 'User deleted'
        ], 200);
    }
}
