@extends('layouts.app')
@section('content')
    <div class="container text-center mt-5">
        <h2>Redirecting you to your booking...</h2>
        <p>If you are not redirected automatically, <a href="{{ $target }}">click here</a>.</p>
    </div>
    @include('partials.ga-tag')
    <script>
        setTimeout(function() {
            window.location.href = @json($target);
        }, 2000);
    </script>
@endsection
