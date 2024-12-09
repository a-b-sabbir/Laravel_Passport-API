<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email",
            "password" => "required",
            "confirm_password" => "required|same:password"
        ]);
        if ($validator->fails()) {
            return response()->json([

                "status" => false,
                "message" => "Validation Error",
                "errors" => $validator->errors()->all()
            ]);
        }

        $user = User::create(
            [
                "name" => $request->name,
                "email" => $request->email,
                "password" => bcrypt($request->password)
            ]
        );

        $response = [];
        $response['token'] = $user->createToken("MyApp")->accessToken;
        $response['user'] = $user->name;
        $response['email'] = $user->email;


        return response()->json([
            "status" => true,
            "message" => "User registered",
            "data" => $response
        ]);
    }

    public function login(Request $request)
    {
        if (Auth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ])) {
            $user = Auth::user();

            $response = [];
            $response['token'] = $user->createToken("MyApp")->accessToken;
            $response['user'] = $user->name;
            $response['email'] = $user->email;

            return response()->json([
                "status" => true,
                "message" => "User logged in",
                "data" => $response
            ]);
        }
        return response()->json([
            "status" => false,
            "message" => "User not registered",
            "data" => null
        ]);
    }
}
