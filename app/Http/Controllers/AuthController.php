<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $user = User::where("email", "=", $request->email)->first();

        if (isset($user->id)) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken("auth_token")->plainTextToken;

                return response()->json([
                    "message" => "usuario loggeado",
                    "access_token" => $token
                ], 200);
            } else {
                return response()->json([
                    "message" => "Invalid Password"
                ], 401);
            }
        } else {
            return response()->json([
                "message" => "Invalid user"
            ], 404);
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            "message" => "logout exitoso"
        ], 200);
    }

}
