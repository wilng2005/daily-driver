<!DOCTYPE html>
<html lang="en">

<head>
@include('partials.gtm-head')
    

@if (App::environment('production'))
    <!-- HTML for production environment -->

    <!-- Basic Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$insight->title}}</title>
    <meta name="description" content="<p>{!! $insight->description_html !!}</p>">

    <!-- Open Graph Meta Tags for social media sharing -->
    <meta property="og:title" content="{{$insight->title}}">
    <meta property="og:description" content="<p>{!! $insight->description_html !!}</p>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://greater.than.today">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{$insight->title}}">
    <meta name="twitter:description" content="<p>{!! $insight->description_html !!}</p>">
    
    <!-- Keywords -->
    <meta name="keywords" content="{{$insight->keywords}}">

    <!-- Author -->
    <meta name="author" content="Psalm12Seven Private Limited">

    <!-- Additional Meta Tags -->
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">
    
@else
    <!-- HTML for non-production environments -->
     <!-- Basic Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staging - Tech Leadership Coaching</title>
    <meta name="description" content="Staging environment for Tech Leadership Coaching. This site is for testing purposes only.">

    <!-- Robots Meta Tag to prevent indexing -->
    <meta name="robots" content="noindex, nofollow">

@endif
    
    
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$insight->title}}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- <link href="https://fonts.googleapis.com/css2?family=Proxima+Nova:wght@300;400;600;700&display=swap" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Domine&display=swap" rel="stylesheet">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/blog/">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{asset('slick/slick.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('slick/slick-theme.css')}}" />

    <link rel="stylesheet" href="{{asset('sass/main.css')}}">
</head>

<body>
    @include('partials.gtm-body')
    @include('partials.nav')

    <main>
@foreach ($insight->sections as $section)

  <section id="content-section-{{ $section->id }}" class="background--{{ $section->background_color}}">
            <div class="container">
                <div class="d-flex align-items-center row">
                    @if ($loop->iteration % 2 == 0)
                        <div class="col-md-5">
                            <img src="{{ asset('images/' . $section->image_filename) }}" class="icon-image full" alt="">
                        </div>
                    @endif
                    <div class="col-md-7 pe-5">
                        <div>
                            @if ($loop->first)
                                <h1>{{ $section->header }}</h1>
                            @else
                                <h2>{{ $section->header }}</h2>
                            @endif
                            <br/>
                            {!! $section->description_html !!}
                        </div>
                    </div>
                    @if ($loop->iteration % 2 == 1)
                        <div class="col-md-5">
                            <img src="{{ asset('images/' . $section->image_filename) }}" class="icon-image full" alt="">
                        </div>
                    @endif
                </div>
            </div>
        </section>
        @endforeach
       
        @include('partials.bottom-section')
                                

</html>