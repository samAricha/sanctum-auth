<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    use HttpResponses;



    public function register(RegisterUserRequest $request)
    {
        $request->validated($request->only(['name', 'email', 'phone', 'password']));
//        $request->validated($request->all());


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return $this->success([
            'user' => $user,
            'access_token' => $user->createToken('API Token')->plainTextToken
        ]);
    }


    public function login(LoginUserRequest $request)
    {
        $validatedData = $request->validated();
        $user = User::where('email', $validatedData['username'])
            ->orWhere('phone', $validatedData['username'])
            ->first();
        if (!$user || !Auth::attempt(['email' => $user->email, 'password' => $validatedData['password']])) {
            return $this->error('', 'Credentials do not match', 401);
        }

        return $this->success([
            'user' => $user,
            'access_token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->success([
            'message' => 'You have succesfully been logged out and your token has been removed'
        ]);
    }

    public function me(Request $request)
    {
        return $request->user();
    }

}
