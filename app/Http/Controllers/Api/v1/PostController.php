<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class PostController extends Controller
{
    /**
     * Get all posts
     *
     * @OA\Get(
     *     path="/api/v1/posts",
     *     operationId="index",
     *     tags={"Posts"},
     *     summary="Get All Posts API",
     *     description="this is an api by which we can show all posts",
     *     @OA\Response(
     *         response=200,
     *         description="show all posts",
     *         @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found",
     *         @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No posts found",
     *         @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to execute api request",
     *         @OA\JsonContent(),
     *     ),
     * )
     */
    public function index(): JsonResponse
    {
        $posts = Post::all();
        if($posts->isEmpty())
        {
            return Response::jsonResponse(null, 'No posts found', 204);
        }
        return Response::jsonResponse($posts, 'show all posts', 200);
    }

    /**
     * Create new user
     *
     * @OA\Post(
     *     path="/api/v1/posts/store",
     *     operationId="store",
     *     tags={"Create"},
     *     summary="Post Create API",
     *     description="this is an api by which we can create post",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  required={"title", "content", "author_id"},
     *                  @OA\Property(property="title", type="text"),
     *                  @OA\Property(property="content", type="text"),
     *                  @OA\Property(property="author_id", type="integer"),
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="New post created",
     *         @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="New post created",
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
     *         description="Validation error",
     *         @OA\JsonContent(),
     *     )
     * )
     */
    public function store(PostStoreRequest $request): JsonResponse
    {
        $post = Post::create($request->validated());
        return Response::jsonResponse($post, 'new post created', 201);
    }

    /**
     * Show a post
     *
     * @OA\Get(
     *     path="/api/v1/posts/show/{post}",
     *     operationId="show",
     *     tags={"Show"},
     *     summary="Get Post API",
     *     description="this is an api by which we can show a post",
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="post id",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="show post",
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
     * )
     */
    public function show(Post $post): JsonResponse
    {
        if(!$post)
        {
            return Response::jsonResponse($post, 'Resource not found', 404);
        }
        return Response::jsonResponse($post, 'show post', 200);
    }

    /**
     * Update a post
     *
     * @OA\Put(
     *     path="/api/v1/posts/update/{post}",
     *     operationId="update",
     *     tags={"Update"},
     *     summary="update post",
     *     description="update a post",
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="post id",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  required={"title", "content"},
     *                  @OA\Property(property="title", type="text"),
     *                  @OA\Property(property="content", type="text"),
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="post updated",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation erroe"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to execute api request",
     *         @OA\JsonContent(),
     *     ),
     * )
     */
    public function update(PostUpdateRequest $request, Post $post): JsonResponse
    {
        if(!$post)
        {
            return Response::jsonResponse($post, 'Resource not found', 404);
        }
        $post->update($request->validated());
        return Response::jsonResponse($post, 'post updated', 200);

    }

    /**
     * Delete a post
     *
     * @OA\Delete(
     *     path="/api/v1/posts/destroy/{post}",
     *     operationId="delete",
     *     tags={"Delete"},
     *     summary="delete post",
     *     description="delete a post",
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="post id",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="post deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to execute api request",
     *         @OA\JsonContent(),
     *     ), 
     * )
     */
    public function destroy(Post $post): JsonResponse
    {
        if(!$post)
        {
            return Response::jsonResponse(null, 'Resource not found', 404);
        }
        $post->delete();
        return Response::jsonResponse(null, 'post deleted', 204);
    }
}