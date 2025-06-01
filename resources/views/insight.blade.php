<!DOCTYPE html>
<html lang="en">


@php
$title=$insight->title;
$description=$insight->description;
$image_path=asset('images/' . $insight->image_filename);
$keywords=$insight->keywords;
@endphp
@include('partials.meta-head')



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