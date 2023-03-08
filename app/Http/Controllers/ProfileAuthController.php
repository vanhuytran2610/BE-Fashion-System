<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthChangePasswordRequest;
use App\Http\Requests\AuthUpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileAuthController extends Controller
{
    public function getAllUsers() {
        $this->authorize('authorize');
        $users = User::all();
        $users->load('role:id,name');

        return response()->json([
            'status' => 'OK',
            'data' => $users
        ], 200);
    }

    public function getAuthProfile() {
        $user = auth()->user();

        return response()->json([
            'status' => 'OK',
            'data' => $user
        ], 200);
    }

    public function changePassword(AuthChangePasswordRequest $request) {
        $user = auth()->user();

        if (Hash::check($request->old_password, $user->password)) {
            User::whereId(auth()->user()->id)->update(['password' => Hash::make($request->password)]);
            return response()->json([
                'status' => 'OK',
                'message' => 'Change password successfully'
            ], 200);
        }
        else {
            return response()->json([
                'status' => 'Error',
                'message' => 'Old password does not matched'
            ],400);
        }
    }

    public function updateProfile (AuthUpdateProfileRequest $request) 
    {                                                                                                                                                                                                                                                                                                  
        $user = $request->user();

        $user->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname
        ]);

        return response()->json([
            'status' => 'OK',
            'message' => 'User profile was updated successfully',
            'data' => $user
        ]);
    }
}
