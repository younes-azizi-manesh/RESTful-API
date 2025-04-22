<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="User authentication"
 * )
 */

class AuthController extends Controller
{
    /**
     * Register new user
     *
     * @OA\Post(
     *     path="/api/register",
     *     operationId="registerUser",
     *     tags={"Authentication"},
     *     summary="Register user,
     *     description="Register user in system",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AuthRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Register successed",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Register successed"),
     *             @OA\Property(property="user", ref="#/components/schemas/User"),
     *             @OA\Property(property="token", type="string", example="1|abcdef123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function register(AuthRegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());
        $token = $user->createToken('register-token')->plainTextToken;
        return response()->json([
            'message' => 'Register successed',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * login user
     *
     * @OA\Post(
     *     path="/api/login",
     *     operationId="loginUser",
     *     tags={"Authentication"},
     *     summary="login user",
     *     description="login user and access the token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AuthRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success login",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Login successed"),
     *             @OA\Property(property="user", ref="#/components/schemas/User"),
     *             @OA\Property(property="token", type="string", example="1|abcdef123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="failed login",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Login is failed!")
     *         )
     *     )
     * )
     */
    public function login(AuthLoginRequest $request): JsonResponse
    {
        if (!auth()->attempt($request->validated())) {
            return response()->json([
                'message' => 'Logged in failed!',
            ], 401);
        }

        $user = $request->user();
        $token = $user->createToken('login-token')->plainTextToken;
        return response()->json([
            'message' => 'Logged in successfully',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'logged out successfully',
        ], 200);
    }
}
