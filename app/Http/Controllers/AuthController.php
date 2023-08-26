<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        //dd('hello');
        $token = $user->createToken('api-token')->accessToken;
        $token_value = DB::
                    table('personal_access_tokens')
                ->where('tokenable_id', $user->id)
                ->latest('id') // Use latest to get the most recent token
                ->value('token');
        return response()->json(['user' => $user, 'token' => $token, 'token_value'=>$token_value], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            //dd($user);
            $token = $user->createToken('api-token')->accessToken;
            
            $token_value = DB::
                    table('personal_access_tokens')
                ->where('tokenable_id', $user->id)
                ->latest('id') // Use latest to get the most recent token
                ->value('token');
            return response()->json(
                ['user' => $user, 
                'token' => $token,
                'token_value'=>$token_value,
                'message' => "Login Successful"], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }
    
}
