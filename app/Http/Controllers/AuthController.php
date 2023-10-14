<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request){
        if (!Auth::attempt($request->only("email", "password"))) {
            return response([
                "message" => "Invalid credentials"
            ], Response::HTTP_UNAUTHORIZED);
        }
        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie("token", $token, 24 * 60);

        return response([
            "token" => $token,
            "user"=>UserResource::make($request->user())
        ])->withCookie($cookie);
    }

    public function user(Request $request)
    {
        return  UserResource::make($request->user());
    }

    public function logout()
    {
        // Auth::logout();
        // Cookie::forget("token");
        Auth::guard('sanctum')->user()->tokens()->delete();
        return response([
            "message" => "Deconnexion réussie !"
        ]);
    }
}
