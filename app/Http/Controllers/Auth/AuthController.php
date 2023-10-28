<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\User as UserResource;

class AuthController extends Controller
{

    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->middleware(['auth:sanctum'])->only(['revokeToken']);
        $this->authService = $authService;
    }

    public function registerWithEmailAndPassword(RegisterRequest $request)
    {
        $user = $this->authService->registerWithEmailAndPassword($request->only(['name', 'email', 'password']));
        if ($user instanceof User) {
            $token = $user->createToken($user->email)->plainTextToken;
            return response()->json([
                'token' => $token,
                'user'  => new UserResource($user)
            ], JsonResponse::HTTP_CREATED);
        }
    }

    public function loginWithEmailAndPassword(Request $request)
    {
        $request->validate([
            'email'     => ['required', 'string'],
            'password'  => ['required', 'string'],
        ]);
        $user = $this->authService->loginWithEmailAndPassword($request->only(['email', 'password']));
        if ($user instanceof User) {
            $token = $user->createToken($user->email)->plainTextToken;
            return response()->json([
                'token' => $token,
                'user'  => new UserResource($user)
            ], JsonResponse::HTTP_OK);
        } else {
            return response()->json([
                'message'     => 'The provided credentials are incorrect.',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Get authenticated user
     */
    public function getAuthenticatedUser()
    {
        try {
            $user = User::findOrFail(auth()->id());
            return new UserResource($user);
        } catch (\Exception $exception) {
            return Response::json([
                'message' => $exception->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Check username before login

     * @param  App\Http\Requests\Auth\LoginCheckUsernameRequest $request;
     * @return \Illuminate\Http\Response
     */
    public function checkUsername(Request $request)
    {
        $request->validate([
            'username'  => ['required', 'string', 'exists:users,username'],
        ]);
        $user = User::where('username', $request->username)->first();
        return new UserResource($user);
    }



    /**
     * Send reset password link.
     */
    public function sendResetPasswordLink(Request $request)
    {
        $request->validate([
            'email'     => ['required', 'email', 'exists:users,email'],
        ]);
        $result = $this->authService->sendResetPasswordLink($request->only(['email']));
        if ($result) return response()->json([
            'message'   => 'A Reset password link has been send to your email'
        ], JsonResponse::HTTP_OK);
    }


    public function verifyTokenPasswordReset(Request $request)
    {
        $request->validate([
            'token'     => ['required', 'string'],
            'email'     => ['required', 'email', 'exists:users,email']
        ]);
        $result = $this->authService->verifyTokenPasswordReset($request->only(['email', 'token']));
        if ($result) {
            return new UserResource($result);
        } else {
            return response()->json([
                'message'   => 'Invalid token or token doesn\'t exists',
                'status'    => true,
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password'  => ['required', 'confirmed'],
            'token'     => ['required', 'string'],
            'email'     => ['required', 'email', 'exists:users,email'],
        ]);
        $user = $this->authService->resetPassword($request->only([
            'password', 'token', 'email'
        ]));
        if ($user) {
            $token = $user->createToken($user->email)->plainTextToken;
            return response()->json([
                'token' => $token,
                'user'  => new UserResource($user)
            ], JsonResponse::HTTP_OK);
        } else {
            return response()->json([
                'message'   => 'Opss, we can not reset your password. Maybe you already did ? Otherwise please try reset password again.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Revoke token from the database
     */
    public function revokeToken(Request $request)
    {
        $request->validate(['currentAccessToken' => ['required', 'string']]);
        $result = $this->authService->deleteCurrentAccessToken($request->currentAccessToken);
        if ($result) {
            return response()->json([
                'message'   => 'Token has been delete!'
            ], JsonResponse::HTTP_OK);
        } else {
            return response()->json([
                'message'   => 'Failed to remove access token'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
