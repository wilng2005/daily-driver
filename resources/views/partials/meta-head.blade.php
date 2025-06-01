<head>
@include('partials.gtm-head')

@if (App::environment('production'))
    <!-- HTML for production environment -->

    <!-- Basic Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$insight->title}}</title>
    <meta name="description" content="{{ $insight->description }}">

    <!-- Open Graph Meta Tags for social media sharing -->
    <meta property="og:title" content="{{$insight->title}}">
    <meta property="og:description" content="{{ $insight->description }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/' . $section->image_filename) }}">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{$insight->title}}">
    <meta name="twitter:description" content="{{ $insight->description }}">

<meta name="twitter:image" content="{{ asset('images/' . $section->image_filename) }}">
    
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
    
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- <link href="https://fonts.googleapis.com/css2?family=Proxima+Nova:wght@300;400;600;700&display=swap" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Domine&display=swap" rel="stylesheet">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{asset('slick/slick.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('slick/slick-theme.css')}}" />

    <link rel="stylesheet" href="{{asset('sass/main.css')}}">
</head>