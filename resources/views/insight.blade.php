<!DOCTYPE html>
<html lang="en">


@php
$title=$insight->title;
$description=$insight->description;
$image_filename=$insight->image_filename;
$keywords=$insight->keywords;
@endphp
@include('partials.meta-head')



<body>
    @include('partials.gtm-body')
    @include('partials.nav')

    <main>
@foreach ($insight->sections as $section)

  @php
    $bgSequence = ['white', 'yellow'];
    $bgColor = !empty($section->background_color) ? $section->background_color : $bgSequence[($loop->index) % 2];
  @endphp
  <section id="content-section-{{ $section->id }}" class="background--{{ $bgColor }}">
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
    @if ($loop->index%4==2)
    @php
        $ctaColors = ['white', 'blue', 'yellow'];
        $randomCtaColor = $ctaColors[array_rand($ctaColors)];
    @endphp
    <section class="background--blue">
        <div class="container">
            <div class="d-flex align-items-center row">

                <div class="col-md-7 pe-5">
                    <div>
                            <h2>Ready to Level Up — But Not Sure Where to Start?</h2>         
                        <br/>

                        <p>You’ve got big goals—but something’s not clicking. Stress is rising. Focus is scattered. Your leadership feels reactive, not strategic.</p>
                        <p>That’s where I come in.</p>
                        <p>In just one free session, you’ll:</p>
                        <ul>
                            <li>Unpack the real blockers behind your current challenges</li>
                            <li>Discover your unique edge—what you <em>actually</em> do best</li>
                            <li>Get a clear, steps to move forward with confidence</li>
                        </ul>
                        <p>Whether you’re a founder, team lead, or ambitious professional, this isn’t a sales pitch. It’s a powerful 1-hour strategy session designed to give you clarity—fast.</p>
                        <blockquote>No fluff. No pressure. Just a chance to reset your momentum.</blockquote>
                        <a href="https://cal.com/wilng/free-coaching-session" target="_blank"
                        class="read-more">Book a Free Coaching Call &rarr;
                        </a>
                    </div>
                </div>
     
                <div class="col-md-5">
                    <img src="{{ asset('images/success-asset-1.png') }}" class="icon-image full" alt="">
                </div>
            
            </div>
        </div>
    </section>
    @endif
@endforeach
   
@include('partials.bottom-section')
                                

</html>