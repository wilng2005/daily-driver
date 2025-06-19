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