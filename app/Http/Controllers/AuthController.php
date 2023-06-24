<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:100',
            'confirm_password' => 'required|same:password',
            'firstname' => 'required|min:2|max:100',
            'lastname' => 'required|min:2|max:100',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->messages()
            ]);
        } else {
            $new_user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'province_code' => $request->province_code,
                'district_code' => $request->district_code,
                'ward_code' => $request->ward_code,
                'address' => $request->address,
                'phone' => $request->phone,
            ]);

            $token = $new_user->createToken($new_user->email . '_Usertoken')->plainTextToken;

            return response()->json([
                'status' => 201,
                'message' => 'Register Successfully',
                'token' => $token,
                'data' => $new_user
            ], 201);
        }
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:100'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->messages()
            ]);
        } else {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'status' => 'Invalid',
                    'message' => 'Email does not exist, please try again!',
                ]);
            } else if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Password was wrong, please re-enter',
                ]);
            } else {
                // 1 is Admin
                if ($user->role_id == 1) {
                    $role = 'Admin';
                    $token = $user->createToken($user->email . '_Admintoken', ['server:admin'])->plainTextToken;
                }
                // 2 is User
                else if ($user->role_id == 2) {
                    $role = 'User';
                    $token = $user->createToken($user->email . '_Usertoken', [''])->plainTextToken;
                }

                return response()->json([
                    'status' => 200,
                    'message' => 'Login Successfully',
                    'token' => $token,
                    'data' => $user,
                    'role' => $role
                ]);
            }
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Logout Successfully'
        ]);
    }
}
