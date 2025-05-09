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
    $posts = \App\Models\Post::where('status', 'published')
        ->where('published_at', '<=', now())
        ->orderBy('published_at', 'desc')
        ->get();

    $careerMessages = [
        "I feel like I’m constantly putting out fires, but not really growing.",
        "I’m stuck. I know something needs to change, but I don’t know what.",
        "I’ve hit a ceiling in my business and can’t see how to move forward.",
        "I’m burning out, but I don’t want to slow down and lose momentum.",
        "I don’t have anyone I can really talk to about this stuff.",
        "Things look fine on the outside, but inside I’m stressed all the time.",
        "I want to lead better, but I keep repeating the same patterns.",
        "I’m scared I’ll mess this up if I keep going like this."
    ];
    $randomCareerMessage = \Illuminate\Support\Arr::random($careerMessages);

    return view('tech-leads', [
        'posts' => $posts,
        'randomCareerMessage' => $randomCareerMessage,
    ]);
    //return view('home');
    // $tag = Tag::where('slug', 'home')
    //     ->where('published_at', '<=', now())
    //     ->orderBy('published_at', 'desc')
    //     ->first();

    // return view('tag', [
    //     'tag' => $tag,
    // ]);

});

// Route::get('/tech-leads', function () {
//     $posts = \App\Models\Post::where('status', 'published')
//         ->where('published_at', '<=', now())
//         ->orderBy('published_at', 'desc')
//         ->get();

//     $careerMessages = [
//         "I feel like I’m constantly putting out fires, but not really growing.",
//         "I’m stuck. I know something needs to change, but I don’t know what.",
//         "I’ve hit a ceiling in my business and can’t see how to move forward.",
//         "I’m burning out, but I don’t want to slow down and lose momentum.",
//         "I don’t have anyone I can really talk to about this stuff.",
//         "Things look fine on the outside, but inside I’m stressed all the time.",
//         "I want to lead better, but I keep repeating the same patterns.",
//         "I’m scared I’ll mess this up if I keep going like this."
//     ];
//     $randomCareerMessage = \Illuminate\Support\Arr::random($careerMessages);

//     return view('tech-leads', [
//         'posts' => $posts,
//         'randomCareerMessage' => $randomCareerMessage,
//     ]);
// });

// Route::get('/churches-and-charities', function () {
//     return view('churches-and-charities');
// });

// Route::get('/tag/{slug}', function (string $slug) {
//     $tag = Tag::where('slug', $slug)
//         ->where('published_at', '<=', now())
//         ->orderBy('published_at', 'desc')
//         ->first();

//     if (!$tag) {
//         abort(404, 'Page not found');
//     }

//     return view('tag', [
//         'tag' => $tag,
//     ]);
// });

// Route::get('/articles', function () {
//     $posts = Post::where('status', 'published')
//         ->where('published_at', '<=', now())
//         ->orderBy('published_at', 'desc')
//         ->get();

//     return view('articles', [
//         'posts' => $posts,
//     ]);
// });

// Route::get('/post/{slug}', function (string $slug) {
//     $post = Post::where('slug', $slug)
//         ->where('published_at', '<=', now())
//         ->orderBy('published_at', 'desc')
//         ->first();

//     return view('post', [
//         'post' => $post,
//     ]);
// })->name('post');

// Route::get('/about', function () {
//     return view('about');
// });

Route::get('/article/five-science-backed-strategies', function () {
    return view('articles.five-science-backed-strategies');
});

//Route::redirect('/', '/nova');
