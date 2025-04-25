@extends('layouts.app')
@section('content')
<link href="/css/articles-custom.css" rel="stylesheet">
<section class="background--white py-5">
    <div class="container">
        <h1 style="font-family: 'Montserrat', sans-serif; font-size: 2rem; color: #222; font-weight: 700;">Insights & Articles</h1>
        <p>Curated insights, practical strategies, and real stories to help you perform at your best and thrive in work and life.</p>
    </div>
</section>

<section class="background--yellow py-5">
    <div class="container">
        <div class="row gx-5 gy-4">
            <div class="col-12">
                @if($posts->count())
                    <div class="articles-grid">
                        @foreach($posts as $post)
                            <div class="article-card mb-3" style="border-radius: 0;">
                                <div class="p-4 d-flex flex-column h-100">
                                    <h3 class="article-title mb-2" style="color: #222;">
                                        <a href="{{ url('/post/'.$post->slug) }}" class="stretched-link text-decoration-none" style="color: #222;">{{ $post->title }}</a>
                                    </h3>
                                    <div class="text-muted small mb-2">
                                        {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Unpublished' }}
                                    </div>
                                    <div class="article-excerpt mb-3 flex-grow-1">
    {{ Str::limit(strip_tags(Str::markdown($post->content)), 160) }}
</div>
                                    <div class="mt-auto">
                                        <a href="{{ url('/post/'.$post->slug) }}" class="read-more">Read More &rarr;</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>No articles found.</p>
                @endif
            </div>

        </div>
    </div>
</section>
@endsection
