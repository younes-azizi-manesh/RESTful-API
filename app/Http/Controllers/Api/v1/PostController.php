<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Posts",
 *     description="management posts"
 * )
 */
class PostController extends Controller
{
    /**
     * show all posts
     *
     * @OA\Get(
     *     path="/api/posts",
     *     operationId="getAllPosts",
     *     tags={"Posts"},
     *     summary="all posts",
     *     description="get all posts",
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="show all posts"),
     *             @OA\Property(
     *                 property="posts",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Post")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $posts = Post::all();
        return response()->json([
            'message' => 'show all posts',
            'posts' => $posts
        ], 200);
    }

    /**
     * create new post
     *
     * @OA\Post(
     *     path="/api/posts",
     *     operationId="createPost",
     *     tags={"Posts"},
     *     summary="create new post",
     *     description="create a post",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StorePostRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="post created",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="new post created"),
     *             @OA\Property(property="post", ref="#/components/schemas/Post")
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
    public function store(PostStoreRequest $request): JsonResponse
    {
        $post = Post::create($request->validated());
        return response()->json([
            'message' => 'new post created',
            'post' => $post
        ], 201);
    }

    /**
     * post detail
     *
     * @OA\Get(
     *     path="/api/posts/{post}",
     *     operationId="getPostById",
     *     tags={"Posts"},
     *     summary="show a post",
     *     description="show post detail",
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="post id",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="show post"),
     *             @OA\Property(property="post", ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="post not found"
     *     )
     * )
     */
    public function show(Post $post): JsonResponse
    {
        return response()->json([
            'message' => 'show post',
            'post' => $post
        ], 200);
    }

    /**
     * update post
     *
     * @OA\Put(
     *     path="/api/posts/{post}",
     *     operationId="updatePost",
     *     tags={"Posts"},
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
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdatePostRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="post updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="post updated"),
     *             @OA\Property(property="post", ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="post not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="validation erroe"
     *     )
     * )
     */
    public function update(PostUpdateRequest $request, Post $post): JsonResponse
    {
        $post->update($request->validated());
        return response()->json([
            'message' => 'post updated',
            'post' => $post
        ], 200);
    }

    /**
     * حذف پست
     *
     * @OA\Delete(
     *     path="/api/posts/{post}",
     *     operationId="deletePost",
     *     tags={"Posts"},
     *     summary="delete",
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
     *         description="post not found"
     *     )
     * )
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();
        return response()->json( null, 204);
    }
}