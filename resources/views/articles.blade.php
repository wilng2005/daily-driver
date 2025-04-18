@extends('layouts.app')
@section('content')
<link href="/css/articles-custom.css" rel="stylesheet">
<div class="article-hero mb-5">
    <h1>Insights & Articles</h1>
    <p>Curated strategies, evidence-based advice, and real stories to help you thrive in your tech career and beyond.</p>
</div>
<div class="container-fluid px-0">
    <div class="row gx-5 gy-4">
        <div class="col-lg-8">
            @if($posts->count())
                <div class="articles-grid">
                    @foreach($posts as $post)
                        <div class="article-card mb-3">
                            <div class="p-4 d-flex flex-column h-100">
                                <h3 class="article-title mb-2">
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
            <div class="articles-sidebar">
                <h4 class="mb-3">Feeling stuck?</h4>
                <p class="mb-3">Instead of trying to make big changes, focus on small, consistent progress. These small improvements add up and build a solid foundation for success.</p>
                <hr>
                <h5 class="mt-4 mb-3">Resources</h5>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <a href="https://youtu.be/oHDq1PcYkT4?si=sSJpajL9q1XvSUap" target="_blank" class="read-more">Wanna get great at something? Get a coach <span class="text-muted small">(TED Talk by Atul Gawande)</span></a>
                    </li>
                    <li class="mb-3">
                        <a href="https://youtu.be/y2zZISLiIB4?si=YLf8RCdu75o8kQHK" target="_blank" class="read-more">Great leaders inspire others to do great things <span class="text-muted small">(Simon Sinek)</span></a>
                    </li>
                    <li>
                        <a href="https://youtu.be/q7a5TIzOmeQ?si=FshCU1FG7nMooUOS" target="_blank" class="read-more">Building your inner coach <span class="text-muted small">(TEDx Talk by Brett Ledbetter)</span></a>
                    </li>
                </ul>
                <hr>
                <div class="text-center mt-4">
                    <a href="https://cal.com/wilng/" class="btn btn-primary px-4 py-2" target="_blank">Start your coaching conversation</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
