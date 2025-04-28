<?php

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {

    return view('tech-leads');
    //return view('home');
    // $tag = Tag::where('slug', 'home')
    //     ->where('published_at', '<=', now())
    //     ->orderBy('published_at', 'desc')
    //     ->first();

    // return view('tag', [
    //     'tag' => $tag,
    // ]);

});

Route::get('/tech-leads', function () {
    return view('tech-leads');
});

Route::get('/churches-and-charities', function () {
    return view('churches-and-charities');
});

Route::get('/tag/{slug}', function (string $slug) {
    $tag = Tag::where('slug', $slug)
        ->where('published_at', '<=', now())
        ->orderBy('published_at', 'desc')
        ->first();

    if (!$tag) {
        abort(404, 'Page not found');
    }

    return view('tag', [
        'tag' => $tag,
    ]);
});

Route::get('/articles', function () {
    $posts = Post::where('status', 'published')
        ->where('published_at', '<=', now())
        ->orderBy('published_at', 'desc')
        ->get();

    return view('articles', [
        'posts' => $posts,
    ]);
});

Route::get('/post/{slug}', function (string $slug) {
    $post = Post::where('slug', $slug)
        ->where('published_at', '<=', now())
        ->orderBy('published_at', 'desc')
        ->first();

    return view('post', [
        'post' => $post,
    ]);
});

Route::get('/about', function () {
    return view('about');
});

//Route::redirect('/', '/nova');
