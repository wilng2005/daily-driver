<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;
use App\Models\Tag;
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

    $tag=Tag::where('slug','home')
            ->where('published_at','<=',now())
            ->orderBy('published_at','desc')
            ->firstOrFail();

    return view('home',[
        'tag'=>$tag,
    ]);
});

Route::get('/tag/{slug}',function(string $slug){
    $tag=Tag::where('slug', $slug)
        ->where('published_at','<=',now())
        ->orderBy('published_at','desc')
        ->firstOrFail();

    return view('tag',[
        'tag'=>$tag,
    ]);
});

Route::get('/post/{slug}',function(string $slug){
    $post=Post::where('slug', $slug)
        ->where('published_at','<=',now())
        ->orderBy('published_at','desc')
        ->firstOrFail();

    return view('post',[
        'post'=>$post,
    ]);
});

Route::get('/about', function () {
    return view('about');
});

//Route::redirect('/', '/nova');
