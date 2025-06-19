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


});

use Illuminate\Support\Str;
use App\Models\Insight;

Route::get('/insights', function () {
    $insights = Insight::published()->orderBy('published_at', 'desc')->get();
    return view('insights-index', [
        'insights' => $insights
    ]);
});

Route::get('/insights/{slug}', function (string $slug) {
    $insight = \App\Models\Insight::where('slug', $slug)
        ->with('sections')
        ->first();
    if (!$insight) {
        abort(404, 'Insight not found');
    }

    // Convert each section description from markdown to HTML
    foreach ($insight->sections as $section) {
        $section->description_html = $section->content_markdown
            ? Str::markdown($section->content_markdown)
            : '';
    }
    return view('insight', [
        'insight' => $insight,
        'sections' => $insight->sections,
    ]);
});

Route::get('/article/{article}', function (string $article) {
    // Only allow slugs with letters, numbers, and dashes
    if (!preg_match('/^[a-zA-Z0-9\-]+$/', $article)) {
        abort(404, 'Page not found');
    }
    $view = "articles.{$article}";
    if (view()->exists($view)) {
        return view($view);
    }
    abort(404, 'Page not found');
});

//Route::redirect('/', '/nova');
