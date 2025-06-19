<!DOCTYPE html>
<html lang="en">

@php

$title="Make Tomorrow Greater";
$description="Strategies and insights to conquer burnout, build resilience, lead confidently, and make tomorrow greater than today. Helping founders, leaders, and software engineers to grow with clarity, courage, and purpose. ";

$image_filename="testimony-1.png";

$keywords="technology, leadership, software engineering, tech leads, CTO coaching, engineering, mental fitness, Agile SCRUM, conflict management, productivity, emotional intelligence, personal wellness";

@endphp

@include('partials.meta-head')

<body>
    @include('partials.gtm-body')

   @include('partials.nav')

    <main>
    @foreach($insights as $insight)
        <section class="{{ $loop->index % 2 === 0 ? 'background--white' : 'background--yellow' }}">
            <div class="container">
                <div class="d-flex align-items-center row">
                    @if($insight->image_filename && $loop->index % 2 == 1)
                        <div class="col-md-5">
                            <img src="{{ asset('images/' . $insight->image_filename) }}" class="icon-image full" alt="{{ $insight->title }}">
                        </div>
                    @endif
                    <div class="col-md-7 pe-5">
                        <div>
                            @if($loop->first)
                                <h1>Latest Insight:</h1>
                            @endif
                            <h2>{{ $insight->title }}</h2>
                            <br/>
                            <p>
                                {{ $insight->description }}
                            </p>
                            <a href="{{ url('/insights/' . $insight->slug) }}" class="read-more">Read More &rarr;</a>
                        </div>
                    </div>
                    @if($insight->image_filename && $loop->index % 2 == 0)
                        <div class="col-md-5">
                            <img src="{{ asset('images/' . $insight->image_filename) }}" class="icon-image full" alt="{{ $insight->title }}">
                        </div>
                    @endif
                </div>
            </div>
        </section>
        @if ($loop->index%4==2)
        @include('partials.cta')
    @endif
        @endforeach
        
        <section class="background--blue">
            <div class="container">
                <div class="d-flex align-items-center row">
                    <div class="col-md-5">
                        <img src="{{asset('images/exhausted-1.png')}}" class="icon-image full" alt="">
                    </div>
                    <div class="col-md-7 pe-5">
                        <div>
                            
                            <h2>Exhausted and Empty? Maybe It’s Burnout</h2>

                            <br/>
                            <p>
                                Feeling utterly drained at the end of each day? If you’re a busy entrepreneur, engineer, or manager running on fumes, you’re not alone – and it’s not a personal failing.
                            </p>
                            <a href="{{ url('/article/exhausted-and-empty-maybe-its-burnout') }}" class="read-more">Read More &rarr;</a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>
    <section class="background--yellow">
            <div class="container">
                <div class="d-flex align-items-center row">
                    <div class="col-md-7 pe-5">
                        <div>
                            <h2>Resilient Leadership: Recovering from Mistakes and Setback</h2>

                            <br/>
                            <p>
                                Mistakes are inevitable, but how you respond defines your resilience. Learn how to navigate setbacks, adapt, and emerge stronger.
                            </p>
                            <a href="{{ url('/article/resilient-leadership-recovering-from-mistakes-and-setbacks') }}" class="read-more">Read More &rarr;</a>
                        </div>
                    </div>
                    <div class="col-md-5">
                            <img src="{{asset('images/fallen-1.png')}}" class="icon-image full" alt="">
                        </div>
                </div>
            </div>
        </section>
        <section class="background--white">
            <div class="container">
                <div class="d-flex align-items-center row">
                   
                        <div class="col-md-5">
                            <img src="{{asset('images/success-asset-1.png')}}" class="icon-image full" alt="">
                        </div>
              
                    <div class="col-md-7 pe-5">
                        <div>
                            <h2>Five Science-Backed Strategies to Recover from Burnout</h2>

                            <br/>
                            <p>
                                Combating burnout requires more than rest alone; it involves actively completing the stress response cycle and replenishing...
                            </p>
                            <a href="{{ url('/article/five-science-backed-strategies') }}" class="read-more">Read More &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
       
        @include('partials.bottom-section')

        <script type="text/javascript">


            $('.testimony-slider').slick({
                arrows: false,
                dots: true,
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                adaptiveHeight: true,
                responsive: [{
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                }, {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                }, ]
                
            });

        </script>
</html>