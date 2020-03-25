<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request)
    {   
    	$validateData = $request->validate([
    		'name' => 'required|max:55',
    		'email' => 'required|email|unique:users',
    		'password' => 'required|confirmed',
    	]);

        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|max:55',
        //     'email' => 'required|email|unique:users',
        //     'password' => 'required|confirmed',
        // ]);
        // if ($validator->fails()) {
        //     return response(['error' => true, 'message' => 'validation error'],404);
        // }


    	$validateData['password'] = bcrypt($request->password);
    	$user = User::create($validateData);

    	$accessToken = $user->createToken('authToken')->accessToken;
    	return response(['user' => $user, 'access_token' => $accessToken]);
    }

    public function login(Request $request)
    {
    	// return "login Catch";
    	// return $request->all();
    	$loginData = $request->validate([
    		'email' => 'required|email',
    		'password' => 'required',
    	]);

    	if (!auth()->attempt($loginData)) {
    		return response(['message' => 'Invalid Credentials']);
    	}

    	$accessToken = auth()->user()->createToken('authToken')->accessToken;

    	return response(['success' => true, 'user' => auth()->user(), 'access_token' => $accessToken]);

    }


    public function details(Request $request)
    {
        return $request->user();
    }

}
