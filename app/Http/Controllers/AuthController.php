<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // added for hashing ..
use Illuminate\Validation\Rule;   //added for password
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
            'userType' => 'required|string|in:media,advertiser', // Ensure userType is one of the specified values
            'terms' => 'required|boolean',
            'password' => 'required|string|confirmed',
        ]);

        // Additional validation to check for missing fields
        $requiredFields = ['firstName', 'lastName', 'email', 'phone', 'userType', 'terms', 'password'];
        $password = $fields['password'];
        $missingFields = array_diff($requiredFields, array_keys($request->all()));

        if (!empty($missingFields)) {
            $message = 'The following fields are missing: ' . implode(', ', $missingFields);
            return response()->json(['error' => $message], 400);
        }

        $user = User::create([
            'firstName' => $fields['firstName'],
            'lastName' => $fields['lastName'],
            'email' => $fields['email'],
            'phone' => $fields['phone'],
            'userType' => $fields['userType'],
            'terms' => $fields['terms'],
            'password' => $fields['password'],
        ]);

        $token = $user->createToken("myapptoken")->plainTextToken;
        $response = [
            "user" => $user,
            "token" => $token,
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email'=>'required|string',
            'password'=>'required|string'
        ]);
        //check user email
        $user = User::where('email', $fields['email'])->first();
        //check if the password do match
        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response([
                "message"=>"incorrect username or password"
            ], 401);
        }
        //generate the token for the user
        $token = $user->createToken("myapptoken")->plainTextToken;
        $response = [
            'message'=>"Login success",
            'user'=>$user,
            'token'=>$token
        ];
        return response($response, 201);

    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return[
            'message'=>'logout success'
        ];

    }


    public function forbiden(Request $request)
    {
        // auth()->user()->tokens()->delete();
        return[
            'message'=>'INVALID ACCESS TOKEN'
        ];

    }


}
