<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(UserLoginRequest $request)
    {
        $request->validated();

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return errorResponse('The provided credentials are incorrect.');
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return successResponse([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return successResponse();

    }

    public function me(Request $request)
    {
        return successResponse($request->user());

    }
}
