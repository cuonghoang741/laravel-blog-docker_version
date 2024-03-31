<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Auth\AuthenticateController;
use App\Http\Controllers\APIController;
use App\Http\Requests\Admin\PostsRequest;
use App\Http\Resources\Post as PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PostController extends APIController
{
    /**
     * Return the posts.
     */
    public function index(Request $request): ResourceCollection
    {
        return PostResource::collection(
            Post::search($request->input('q'))->withCount('comments')->latest()->paginate($request->input('limit', 20))
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostsRequest $request, Post $post): PostResource
    {
        $this->authorize('update', $post);

        $post->update($request->only(['title', 'content', 'posted_at', 'author_id', 'thumbnail_id']));

        return new PostResource($post);
    }


    /**
     * @OA\Post(
     *     path="/api/v1/posts",
     *     summary="Create a new post",
     *     description="Create a new post with specified data",
     *     tags={"Posts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "content", "posted_at", "author_id", "thumbnail_id", "thumb_url", "category_id"},
     *             @OA\Property(property="title", type="string", example="Sample title"),
     *             @OA\Property(property="content", type="string", example="Sample Content"),
     *             @OA\Property(property="posted_at", type="string", format="date-time", example="2024-03-31T12:00:00Z"),
     *             @OA\Property(property="author_id", type="integer", example=1),
     *             @OA\Property(property="thumbnail_id", type="integer", example=1),
     *             @OA\Property(property="thumb_url", type="string", example="https://example.com/thumb.jpg"),
     *             @OA\Property(property="image_url", type="string", example="https://example.com/image.jpg"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="city", type="string", example="New York"),
     *             @OA\Property(property="country", type="string", example="USA"),
     *             @OA\Property(property="type", type="string", example="blog"),
     *             @OA\Property(property="kind", type="string", example="public"),
     *             @OA\Property(property="status", type="string", example="published")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *     )
     * )
     */
    public function store(PostsRequest $request): PostResource
    {
        $this->authorize('store', Post::class);

        return new PostResource(
            Post::create($request->only(Post::$createable))
        );
    }

    /**
     * Return the specified resource.
     */
    public function show(Post $post): PostResource
    {
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): Response
    {
        $this->authorize('delete', $post);

        $post->delete();

        return response()->noContent();
    }


    /**
     * @OA\Get(
     *     path="/api/v1/ping",
     *     summary="Ping API",
     *     description="Check if API is running",
     *     tags={"Ping"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="ok")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */
    public function ping(){
        return "ok";
    }
}
