<!DOCTYPE html>
<html lang="en">

<head>
    

@if (App::environment('production'))
    <!-- HTML for production environment -->
    <!-- Basic Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Tomorrow Greater</title>
    <meta name="description" content="Helping founders, leaders, and software engineers to grow with clarity, courage, and purpose. Conquer burnout, build resilience, lead confidently, and make tomorrow greater than today.">

    <!-- Open Graph Meta Tags for social media sharing -->
    <meta property="og:title" content="Make Tomorrow Greater">
    <meta property="og:description" content="Helping founders, leaders, and software engineers to grow with clarity, courage, and purpose. Conquer burnout, build resilience, lead confidently, and make tomorrow greater than today.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://greater.than.today">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Make Tomorrow Greater">
    <meta name="twitter:description" content="Helping founders, leaders, and software engineers to grow with clarity, courage, and purpose. Conquer burnout, build resilience, lead confidently, and make tomorrow greater than today.">
    
    <!-- Keywords -->
    <meta name="keywords" content="technology, leadership, software engineering, tech leads, CTO coaching, engineering, mental fitness, Agile SCRUM, conflict management, productivity, emotional intelligence, personal wellness">

    <!-- Author -->
    <meta name="author" content="Psalm12Seven">

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
    <title>Greater than Today</title>

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
        <section class="background--white">
            <div class="container">
                <div class="d-flex align-items-end row">
                    <div class="col-12 col-md-8 col-lg-9">
                        <h1>
                        You can’t shortcut the work, but you can enjoy the process.
                        </h1>
                        <img src="{{asset('images/asset-3v2.png')}}" class="icon-image small ms-auto" alt="">
                    </div>
                    <div class="col-12 col-md-4 col-lg-3 py-md-5">
                        <a href="https://cal.com/wilng/free-coaching-session" target=”_blank” class="button px-4">Book a Free Discovery Session</a>
                    </div>
                </div>
            </div>
        </section>
        <section id="meetyourcoach" class="background--half">
            <div class="container">
                <div class="d-flex align-items-center row">
                    <div class="col-md-7 pe-5">
                        <div class="font-p mb-5"><strong>Meet Your Coach</strong></div>
                        <h2 class="mb-3">Wil Ng</h2>
                        <div>
                            <p>Head of Engineering at a FinTech start-up and Co-founder of a software development firm.
                            </p>
                            <p>Wil has been working with software engineers and engineering leaders for over a decade.
                                He excels at building empowered high-performing teams.</p>
                            <p>His background includes Information Systems and Business Management from the Singapore
                                Management University.</p>
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
                        slidesToShow: 2,
                        slidesToScroll: 2,
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