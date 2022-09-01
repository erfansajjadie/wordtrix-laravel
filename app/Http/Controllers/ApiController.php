<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function profile()
    {
        return Auth::user();
    }

    public function updateProfile(Request $request)
    {
        if($request->has("username")) {
            if(User::firstWhere("username", $request->username) != null) {
                return response([
                    "success" => false,
                    "message" => "The user name already exists"
                ], 400);
            }
        }
        Auth::user()->update($request->all());
        return Auth::user();
    }

    public function getRanks()
    {
        return User::orderByDesc("levelId")->get();
    }
}
