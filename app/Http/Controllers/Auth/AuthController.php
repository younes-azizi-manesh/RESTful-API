<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Register new user
     *
     * @OA\Post(
     *     path="/api/register",
     *     operationId="register",
     *     tags={"Register"},
     *     summary="User Registeration API",
     *     description="this is an api by which we can register user",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  required={"name", "email", "password"},
     *                  @OA\Property(property="name", type="text"),
     *                  @OA\Property(property="email", type="email"),
     *                  @OA\Property(property="password", type="password"),
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Register successed",
     *         @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Register successed",
     *         @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found",
     *         @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to execute api request",
     *         @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="validation error",
     *         @OA\JsonContent(),
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
     *
     * @OA\Post(
     *     path="/api/login",
     *     operationId="login",
     *     tags={"Login"},
     *     summary="User Login API",
     *     description="this is an api we which can login user",
     *     @OA\RequestBody(
     *       @OA\JsonContent(),
     *         @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  required={"email", "password"},
     *                  @OA\Property(property="email", type="email"),
     *                  @OA\Property(property="password", type="password"),
     *              )
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="success login",
     *         @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Logged in failed",
     *         @OA\JsonContent(),
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Resource not found",
     *         @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to execute api request",
     *         @OA\JsonContent(),
     *     ),
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

    /**
     *
     * @OA\Post(
     *     path="/api/logout",
     *     operationId="logout",
     *     tags={"logout"},
     *     summary="User Logout API",
     *     description="this is an api we which can logout user",
     *     @OA\RequestBody(),
     *     @OA\Response(
     *         response=200,
     *         description="logged out successfully",
     *         @OA\JsonContent(),
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Resource not found",
     *         @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to execute api request",
     *         @OA\JsonContent(),
     *     ),
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'logged out successfully',
        ], 200);
    }
}
