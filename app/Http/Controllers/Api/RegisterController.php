<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'patronymic'=>'required',
            'email'=>'required|unique:users',
            'password'=>'required|string|min:3|regex:/^(?=.*[A-Z])(?=.*\d).+$/',
            'birth_date'=>'required|date_format:Y-m-d',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'=>$request->last_name,
            'patronymic'=>$request->patronymic,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'birth_date'=>$request->birth_date,
        ]);
        $token = $user->createToken('user_token')->plainTextToken;

        $full_name = $user->last_name . " " . $user->first_name . " " . $user->patronymic;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'name' => $full_name,
                'email' => $user->email
            ],
            'code' => 201,
            'message' => 'Пользователь создан!'
        ]);
    }

    public function login(Request $request){
        $request->validate([
           'email' => 'required|email',
           'password' => 'required|string'
        ]);

        if (Auth::attempt($request->only('email', 'password'))){
            $user = Auth::user();
            $user->tokens()->delete();
            $token = $user->createToken('user_token')->plainTextToken;
            $full_name = $user->last_name . " " . $user->first_name . " " . $user->patronymic;
            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'name' => $full_name,
                    'birth_date' => $user->birth_date,
                    'email' => $user->email
                ],
                "token" => $token
            ]);
        }
        else{
            return response()->json([
                'code' => 401,
                'message' => 'Неправильные логин или Пароль'
            ],401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([],204);
    }
}
