@extends('layouts.app')
@section('content')
<link href="/css/articles-custom.css" rel="stylesheet">
<div class="article-hero mb-5">
    <h1 style="font-family: 'Montserrat', sans-serif; font-size: 2rem; color: #222; font-weight: 700;">Insights & Articles</h1>
    <p>Curated strategies, evidence-based advice, and real stories to help you thrive in your tech career and beyond.</p>
</div>
<div class="container-fluid px-0">
    <div class="row gx-5 gy-4">
        <div class="col-lg-8">
            @if($posts->count())
                <div class="articles-grid">
                    @foreach($posts as $post)
                        <div class="article-card mb-3" style="border-radius: 0;">
                            <div class="p-4 d-flex flex-column h-100">
                                <h3 class="article-title mb-2" style="color: #222;">
                                    <a href="{{ url('/post/'.$post->slug) }}" class="stretched-link text-decoration-none">{{ $post->title }}</a>
                                </h3>
                                <div class="text-muted small mb-2">
                                    {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Unpublished' }}
                                </div>
                                <div class="article-excerpt mb-3 flex-grow-1">
                                    {{ Str::limit(strip_tags($post->content), 160) }}
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
        <div class="col-lg-4">
            
        </div>
    </div>
</div>
@endsection
