<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\User\LoginUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends ApiController
{
    /**
     * @param LoginUserRequest $request
     * @return JsonResponse
     */
    public function login(LoginUserRequest $request): JsonResponse
    {
        $request->validated();

        if (!Auth::attempt($request->only('email', 'password')))
        {
            return $this->error([
                'type' => 'Invalid Credentials',
                'status' => 401,
                'message' => 'The credentials you provided did not match our records.'
            ], 401);
        }

        /** @var User $user */
        $user = User::query()->firstWhere('email', $request->input('email'));

        return $this->ok('Authenticated', [
            'token' => $user->createToken('API Token for ' . $user->email)->plainTextToken
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->ok('');
    }
}
