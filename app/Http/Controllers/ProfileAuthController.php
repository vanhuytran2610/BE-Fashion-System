<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthChangePasswordRequest;
use App\Http\Requests\AuthUpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileAuthController extends Controller
{
    public function getAllUsers()
    {
        $users = User::all();
        $users->load('role:id,name');

        return response()->json([
            'status' => 200,
            'data' => $users
        ]);
    }

    public function getAuthProfile()
    {
        $user = auth()->user();

        return response()->json([
            'status' => 200,
            'data' => $user
        ]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|min:6|max:100',
            'password' => 'required|string|min:6|max:100',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Please enter all information'
            ]);
        }

        $user = auth()->user();

        if (Hash::check($request->old_password, $user->password)) {
            User::whereId(auth()->user()->id)->update(['password' => Hash::make($request->password)]);
            return response()->json([
                'status' => 200,
                'message' => 'Change password successfully'
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Old password does not match'
            ]);
        }
    }

    public function updateProfile(AuthUpdateProfileRequest $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Please login first'
            ]);
        } else {
            $user->update([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'province_code' => $request->province_code,
                'district_code' => $request->district_code,
                'ward_code' => $request->ward_code,
                'address' => $request->address,
                'phone' => $request->phone
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'User profile was updated successfully',
                'data' => $user
            ]);
        }
    }
}
