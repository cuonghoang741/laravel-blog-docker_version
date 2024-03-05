<?php

use App\Http\Controllers\NewsletterSubscriptionController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostFeedController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/posts/feed', [PostFeedController::class, 'index'])->name('posts.feed');
Route::get('/posts', function (){
    $posts = \App\Models\Post::all();
    return view("posts.index",["posts"=>$posts]);
});
Route::get('/posts/{post:slug}', function (\Illuminate\Http\Request $request){
    $post = \App\Models\Post::query()->find();
    return view("posts._post",["post"=>$post]);
});
Route::resource('posts', PostController::class, array("as" => "api"))->only('show');
//Route::resource('posts', PostController::class, array("as" => "api"))->only('showed');
Route::resource('users', UserController::class, array("as" => "api"))->only('show');
Route::resource('posts.comments', PostCommentController::class, array("as" => "api"))->only('index');

Route::get('newsletter-subscriptions/unsubscribe', [NewsletterSubscriptionController::class, 'unsubscribe'])->name('newsletter-subscriptions.unsubscribe');




Route::prefix('/api/v1')->group(function () {
    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        // Comments
        Route::apiResource('comments', CommentController::class)->only('destroy');
        Route::apiResource('posts.comments', PostCommentController::class)->only('store');

        // Posts
        Route::apiResource('posts', PostController::class)->only(['update', 'store', 'destroy']);
        Route::post('/posts/{post}/likes', [PostLikeController::class, 'store'])->name('posts.likes.store');
        Route::delete('/posts/{post}/likes', [PostLikeController::class, 'destroy'])->name('posts.likes.destroy');

        // Users
        Route::apiResource('users', UserController::class)->only('update');

        // Media
        Route::apiResource('media', MediaController::class)->only(['store', 'destroy']);
    });

    Route::post('/authenticate', [AuthenticateController::class, 'authenticate'])->name('authenticate');


    // Posts
    Route::apiResource('posts', PostController::class)->only(['index', 'show']);
    Route::apiResource('users.posts', UserPostController::class)->only('index');


    // Comments
    Route::apiResource('posts.comments', PostCommentController::class)->only('index');
    Route::apiResource('users.comments', UserCommentController::class)->only('index');
    Route::apiResource('comments', CommentController::class)->only(['index', 'show']);

    // Users
    Route::apiResource('users', UserController::class)->only(['index', 'show']);

    // Media
    Route::apiResource('media', MediaController::class)->only('index');
});
