<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResgisterControler extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|unique:users|min:4|email',
            'password' => 'required|string|min:5|max:10'
        ]);

        if ($validator->fails()){
            return response()->json([
                'email' => 'infalid field',
                'errors' => $validator->errors()
            ],422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('ApiToken')->plainTextToken;

        $response = [
            'success'  => 'success',
            'message' => 'Register user berhasil',
            'user'     => $user,
            'accessToken' => $token
        ];

        return response($response, 201);

        
    }
}
