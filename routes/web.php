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

Route::prefix('/ai')->group(function () {
    Route::prefix('/trip-planner')->group(function () {
        Route::get("/",[\App\Http\Controllers\TripPlanController::class,"index"])->name("trip-planner");
        Route::post("/",[\App\Http\Controllers\TripPlanController::class,"createPlan"]);
        Route::get("/{plan}",[\App\Http\Controllers\TripPlanController::class,"show"]);
    });
});

Route::prefix('/api/v2')->group(function () {
    Route::get("postsx",[\App\Http\Controllers\Api\V1\PostController::class,"store"]);
});
Route::apiResource('posts', PostController::class, array("as" => "web"))->only(['update', 'store', 'destroy']);

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

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\UserPasswordController;

Route::redirect('/.well-known/change-password', '/settings/password');

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('settings')->group(function () {
        Route::get('account', [UserController::class, 'edit'])->name('users.edit');
        Route::match(['put', 'patch'], 'account', [UserController::class, 'update'])->name('users.updated');

        Route::get('password', [UserPasswordController::class, 'edit'])->name('users.password');
        Route::match(['put', 'patch'], 'password', [UserPasswordController::class, 'update'])->name('users.password.update');
    });

    Route::resource('comments', CommentController::class, array("as" => "api"))->only(['store', 'destroy']);
    Route::post('/posts/{post}/likes', [PostLikeController::class, 'store'])->name('posts.likes.stored');
    Route::delete('/posts/{post}/likes', [PostLikeController::class, 'destroy'])->name('posts.likes.destroyed');

    Route::resource('newsletter-subscriptions', NewsletterSubscriptionController::class)->only('store');
});

$routePublicV1 = function () {
    Route::group(["prefix" => '/ai'], function () {
        Route::group(["prefix" => '/trip-plan'], function () {
            Route::group(["prefix" => '/cities'], function () {
                Route::get('/',[\App\Http\Controllers\TripPlanController::class,'searchCities']);
                Route::get('/{city:id}/fill-id',[\App\Http\Controllers\TripPlanController::class,'fillCityAdvisorId']);
                Route::get('/{city:id}/locations',[\App\Http\Controllers\TripPlanController::class,'cityLocations']);
            });
        });
    });
};
Route::prefix("web-api/v1")->group($routePublicV1);
