<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\GoogleSignInMobileRequest;
use App\Http\Requests\Auth\LoginWithEmailAndPasswordRequest;
use App\Http\Requests\Auth\RegisterWithEmailAndPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->middleware(['auth:sanctum'])->only(['revokeToken', 'getAuthenticatedUser']);
        $this->authService = $authService;
    }

    /**
     * Register with email & password
     */
    public function registerWithEmailAndPassword(
        RegisterWithEmailAndPasswordRequest $request
    ): \Illuminate\Http\Response | JsonResponse | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            $user   = $this->authService->registerWithEmailAndPassword($request);
            $token  = $user->createToken($user->email)->plainTextToken;
            return response()->json([
                'token'   => $token,
                'user'    => new UserResource($user)
            ], JsonResponse::HTTP_CREATED);
        } catch (Exception $exception) {
            return response([
                'status' => false,
                'message' => $exception->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Login with email & password
     */
    public function loginWithEmailAndPassword(
        LoginWithEmailAndPasswordRequest $request
    ): \Illuminate\Http\Response | JsonResponse | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            $user   = $this->authService->loginWithEmailAndPassword($request);
            $token  = $user->createToken($user->email)->plainTextToken;
            return response()->json([
                'token'   => $token,
                'user'    => new UserResource($user)
            ], JsonResponse::HTTP_OK);
        } catch (Exception $exception) {
            return response([
                'status' => false,
                'message' => $exception->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Google sign in mobile
     */
    public function googleSignInMobile(
        GoogleSignInMobileRequest $request
    ): \Illuminate\Http\Response | JsonResponse | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            $user   = $this->authService->googleSignInMobile($request);
            $token  = $user->createToken($user->email)->plainTextToken;
            return response()->json([
                'token'   => $token,
                'user'    => new UserResource($user)
            ], JsonResponse::HTTP_OK);
        } catch (Exception $exception) {
            return response([
                'status' => false,
                'message' => $exception->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Get authenticated user
     */
    public function getAuthenticatedUser(): \Illuminate\Http\Response | UserResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $user   = User::findOrFail(auth()->id());
            return new UserResource($user);
        } catch (Exception $exception) {
            return response([
                'status' => false,
                'message' => $exception->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Check username before login
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
    public function sendResetPasswordLink(Request $request): \Illuminate\Http\Response | JsonResponse | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'appId' => ['required', 'string']
        ]);
        if ($validator->fails()) {
            return new JsonResponse(
                ['errors' => $validator->errors()],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        try {
            $result = $this->authService->sendResetPasswordLink($request->email, $request->appId);
            return response()->json(['status' => true, 'message' => $result], JsonResponse::HTTP_OK);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    public function verifyTokenPasswordReset(
        Request $request
    ): \Illuminate\Http\Response | UserResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        $request->validate([
            'token'     => ['required', 'string'],
            'email'     => ['required', 'email', 'exists:users,email']
        ]);
        try {
            $result = $this->authService->verifyTokenPasswordReset($request->email, $request->token);
            return new UserResource($result);
        } catch (Exception $exception) {
            return response([
                'status' => false,
                'message' => $exception->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Reset password
     */
    public function resetPassword(
        ResetPasswordRequest $request
    ): \Illuminate\Http\Response | JsonResponse | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            $user   = $this->authService->resetPassword($request);
            $token  = $user->createToken($user->email)->plainTextToken;
            return response()->json([
                'token'   => $token,
                'user'    => new UserResource($user)
            ], JsonResponse::HTTP_OK);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
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
