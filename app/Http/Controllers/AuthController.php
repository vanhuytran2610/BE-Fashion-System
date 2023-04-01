<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(AuthRegisterRequest $request)
    {
        $role_id = Role::find($request->role_id);

        if (!$role_id) {
            return response()->json([
                "status" => "Error",
                "message" => "This Role does not exist",
            ], 404);
        }

        $new_user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id
        ]);

        $token = $new_user->createToken('myapptoken')->plainTextToken;
        $new_user->load('role:id,name');
        return response()->json([
            'status' => 'OK',
            'message' => 'Register Successfully',
            'token' => $token,
            'data' => $new_user
        ], 200);
    }

    public function login(AuthLoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Email or password was wrong, please re-enter',
            ], 400);
        } else {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('myapptoken')->plainTextToken;
                $user->load('role:id,name');
                return response()->json([
                    'status' => 'OK',
                    'message' => 'Login Successfully',
                    'token' => $token,
                    'data' => $user
                ], 200);
            } else {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Email or password was wrong, please re-enter',
                ], 400);
            }
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'OK',
            'message' => 'Logout Successfully'
        ], 200);
    }
}
