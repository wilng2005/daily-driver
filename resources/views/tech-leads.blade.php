<!DOCTYPE html>
<html lang="en">

@php
$title="Make Tomorrow Greater";
$description="Helping founders, leaders, and software engineers to grow with clarity, courage, and purpose. Conquer burnout, build resilience, lead confidently, and make tomorrow greater than today.";
$image_path="asset('images/assetv2.png')";
$keywords="technology, leadership, software engineering, tech leads, CTO coaching, engineering, mental fitness, Agile SCRUM, conflict management, productivity, emotional intelligence, personal wellness";
@endphp

@include('partials.meta-head')

<body>
    @include('partials.gtm-body')

   @include('partials.nav')

    <main>
        <section class="background--white">
            <div class="col-12 container">
                <div class="section-header-con">
                   
<h1 style="font-size:2rem;">
    <em>"{{ $randomCareerMessage }}"</em>
</h1>
                    <div class="mt-5 text-end">
                        <!-- <a href="https://cal.com/wilng/tech-lead-coaching" target=”_blank” class="button">Get
                            Started</a> -->
                    </div>
                </div>
            </div>
        </section>

        <section class="background--yellow">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="mb-4">Making progress can be hard…
                            </h1>
                    </div>
                    <div class="section-slider">
                        <div class="details-each">
                            <h4 class="mb-3">Rampant Layoffs</h4>
                            <p>
                                Many companies are reshaping their workforce due to economic pressures and technological shifts, leaving many feeling uncertain about the future. 
                            </p>
                        </div>
                        <div class="details-each">
                            <h4 class="mb-3">Balancing Work and Parenting</h4>
                            <p>
                                This is often the most immediate and pressing challenge as it affects daily
                                operations and the leader's ability to fulfill both roles effectively.
                            </p>
                        </div>
                        <div class="details-each">
                            <h4 class="mb-3">Leadership skills</h4>
                            <p>
                                Essential for long-term success, developing skills like communication, conflict
                                resolution, and motivation is critical.
                            </p>
                            <img src="{{asset('images/asset-2v2.png')}}" class="icon-image mb-3 ms-auto" alt="">
                        </div>
                        <div class="details-each">
                            <h4 class="mb-3">Managing Team Dynamics</h4>
                            <p>
                                Effectively managing team relationships and dynamics is vital for maintaining a
                                productive and harmonious work environment.
                            </p>
                        </div>
                        <div class="details-each">
                            <h4 class="mb-3">Handling Pressure and Accountability</h4>
                            <p>
                                The increased responsibility and pressure can be significant, impacting
                                decision-making and overall team performance.
                            </p>
                        </div>
                        <div class="details-each">
                            <h4 class="mb-3">Performance Management</h4>
                            <p>
                                Providing constructive feedback and managing performance can directly impact
                                team
                                growth and productivity.
                            </p>
                            <img src="{{asset('images/asset-1v2.png')}}" class="icon-image mb-3 ms-auto" alt="">
                        </div>
                        <div class="details-each">
                            <h4 class="mb-3">Building Trust and Respect</h4>
                            <p>
                                Critical for team cohesion but typically develops over time with consistent
                                leadership behavior.
                            </p>
                        </div>
                        <div class="details-each">
                            <h4 class="mb-3">Maintaining Technical Skills</h4>
                            <p>
                                While important for personal growth and credibility, it is often deprioritized
                                compared to immediate leadership responsibilities.
                            </p>
                        </div>
                        <div class="details-each">
                            <h4 class="mb-3">Time Management</h4>
                            <p>
                                Essential for balancing multiple responsibilities, but can be improved with
                                experience and tools.
                            </p>
                            <img src="{{asset('images/assetv2.png')}}" class="icon-image mb-3 ms-auto" alt="">
                        </div>
                        <div class="details-each">
                            <h4 class="mb-3">Communicating Vision</h4>
                            <p>
                                Establishing a clear direction and ensuring the team understands and aligns with
                                it
                                is fundamental for achieving strategic goals.
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <section id="meetyourcoach" class="background--half">
            <div class="container">
                <div class="d-flex align-items-center row">
                    <div class="col-md-7 pe-5">
                        <div class="font-p mb-5"><strong>Meet Your Coach</strong>
                        </div>
                        <h2 class="mb-3">Wil Ng</h2>
                        <div>
                            <p>Wil is a Business Coach who partners with leaders and teams to clarify strategy and drive focused execution.</p> 
                            <p>With over a decade of experience, he has worked closely with engineers and business leaders as the Head of Engineering at a financial services firm and as a co-founder of a software development company.</p> 
                            <a id="free-discovery-session-bottom" href="https://cal.com/wilng/casual-conversation" target="_blank" class="read-more">Get in touch &rarr;</a>
                        </div>
                        
                    </div>
                    <div class="col-md-5">
                        <img src="{{asset('images/asset.jpg')}}" class="icon-image full" alt="">
                    </div>
                </div>
            </div>
        </section>

        <section id="testimonials" class="background--blue">
            <div class="container">
                <div class="testimony-slider">
                    <div class="d-flex align-items-center row">
                        <div class="px-4 col-12 col-md-7">
                            <h2 class="mb-4 mb-lg-5"><b>
                                    <div class="quote">“</div>
                                    Wil's guidance and insight have broadened my horizon and equipped me with tools to navigate around my challenges." 
                                </b></h2>
                            <p> — Ronny Muliawan, System Architect</p>
                        </div>
                        <div class="px-4 col-12 col-md-5">
                            <img src="{{asset('images/testimony-1.png')}}" class="icon-image full" alt="">
                        </div>
                    </div>
                    <div class="d-flex align-items-center row">
                        <div class="px-4 col-12 col-md-7">
                            <h2 class="mb-4 mb-lg-5"><b>
                                    <div class="quote">“</div>
                                    Initially, I was skeptical, but as we dove deeper into the concepts, my perspective shifted. It has made a real impact not only in my work but also in my personal life."
                                </b></h2>
                            <p> — Adeline Pang, Product Owner</p>
                        </div>
                        <div class="px-4 col-12 col-md-5">
                            <img src="{{asset('images/testimony-2.png')}}" class="icon-image full" alt="">
                        </div>
                    </div>
                    <div class="d-flex align-items-center row">
                        <div class="px-4 col-12 col-md-7">
                            <h2 class="mb-4 mb-lg-5"><b>
                                    <div class="quote">“</div>
                                    Thanks to Wil's guidance, I've felt more prepared to handle the
                                    uncertainties that lie
                                ahead.”
                            </b></h2>
                            <p> — Tony Tong, Chief Technology Officer</p>
                        </div>
                        <div class="px-4 col-12 col-md-5">
                            <img src="{{asset('images/asset-4v2.png')}}" class="icon-image full" alt="">
                        </div>
                    </div>
                    
                    
                    <div class="d-flex align-items-center row">
                        <div class="px-4 col-12 col-md-7">
                            <h2 class="mb-4 mb-lg-5"><b>
                                    <div class="quote">“</div>
                                    Wil’s coaching has been incredibly valuable. He guides me in breaking down complex issues, which makes challenges feel less daunting."
                                </b></h2>
                            <p> — Ain Kamis, Scrum Master</p>
                        </div>
                        <div class="px-4 col-12 col-md-5">
                            <img src="{{asset('images/testimony-3.png')}}" class="icon-image full" alt="">
                        </div>
                    </div>
                    <div class="d-flex align-items-center row">
                        <div class="px-4 col-12 col-md-7">
                            <h2 class="mb-4 mb-lg-5"><b>
                                    <div class="quote">“</div>
                                    I would highly recommend anyone that is seeking to understand themselves better, be it in their mental aptitude or emotional quotient, to give this course a shot. "
                                </b></h2>
                            <p> — Nobie Tan, Real Estate</p>
                        </div>
                        <div class="px-4 col-12 col-md-5">
                            <img src="{{asset('images/testimony-5.png')}}" class="icon-image full" alt="">
                        </div>
                    </div>
                    <div class="d-flex align-items-center row">
                        <div class="px-4 col-12 col-md-7">
                            <h2 class="mb-4 mb-lg-5"><b>
                                    <div class="quote">“</div>
                                    I have gained more self-awareness and clarity in my goals. Wil is also a sincere and committed coach who is very willing to share his experiences."
                                </b></h2>
                            <p> — Michelle Lai, Assistant Vice President</p>
                        </div>
                        <div class="px-4 col-12 col-md-5">
                            <img src="{{asset('images/testimony-4.png')}}" class="icon-image full" alt="">
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