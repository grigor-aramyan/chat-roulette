<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        if (!($request->name && $request->email && $request->password
            && $request->purpose && $request->status
            && $request->age && $request->sex && $request->city
            && $request->question_one && $request->question_two && $request->question_three))
            {
                return response()->json([ 'msg' => 'All fields required' ], 400);
            }

        $email = $request->email;
        $existing_email = User::where('email', $email)->first();
        if ($existing_email) {
            return response()->json([ 'msg' => 'User with this email already exists!' ], 400);
        }

        // TODO: usernames should be generated automatically
        $username = 'username';

      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'purpose' => $request->purpose,
        'status' => $request->status,
        'username' => $username,
        'age' => $request->age,
        'sex' => $request->sex,
        'city' => $request->city,
        'photo' => $request->photo,
        'question_one' => $request->question_one,
        'question_two' => $request->question_two,
        'question_three' => $request->question_three
      ]);

      $token = auth()->login($user);

      return $this->respondWithToken($token);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
