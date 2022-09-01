<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends  Controller
{
    public function login(Request $request)
    {
        $user =  User::firstWhere("deviceId", $request->deviceId);
        $token = auth()->setTTL(9999999999)->login($user);
        if($user != null) {
            return [
                "user" => $user,
                "token" => $token
            ];
        }
        return [
            "success" => false,
            "message" => "Device not registered"
        ];
    }

    public function register(Request $request)
    {
        $user =  User::firstWhere("deviceId", $request->deviceId);

        if($user != null) {
            $user->username = $request->username;
            $user->save();
            $token = auth()->setTTL(9999999999)->login($user);
            return [
                "user" => $user,
                "token" => $token
            ];
        } else {
            $user = User::create([
                "username" => $request->username,
                "deviceId" => $request->deviceId,
            ]);
            JWTAuth::factory()->setTTL(9999999999);
            return [
                "user" => $user,
                "token" => JWTAuth::fromUser($user)
            ];
        }
    }
}
