<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Providers\TripPlanServiceProvider;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Show the application dashboard.
     */

    private function getLatest($request){
        return Post::search($request->input('q'))->orderBy("created_at","desc")
            ->with('author', 'likes', 'category')
            ->withCount('comments', 'thumbnail', 'likes')
            ->paginate(16);
    }

    public function index(Request $request): View
    {
        $posts = Post::search($request->input('q'))->with('author', 'likes', 'category')
            ->withCount('comments', 'thumbnail', 'likes')
            ->latest()
            ->paginate(4);

        $latest = $this->getLatest($request);

        $trending = Post::search($request->input('q'))->orderBy("view","desc")
            ->with('author', 'likes', 'category')
            ->withCount('comments', 'thumbnail', 'likes')
            ->paginate(6);

        return view('posts.index', [
            'posts' => $posts,
            "latest"=>$latest,
            "trending"=>$trending
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Post $post): View
    {
        $post->view = $post->view + 1;
        $post->save();
        $post->comments_count = $post->comments()->count();
        $post->likes_count = $post->likes()->count();

        $latest = $this->getLatest($request);

        return view('posts.show', [
            'post' => $post,
            'latest' => $latest
        ]);
    }

    public function test(){
        $tripPlanServiceProvider = new TripPlanServiceProvider();

        return $tripPlanServiceProvider->test();
    }
}
