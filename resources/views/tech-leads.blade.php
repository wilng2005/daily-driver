<!DOCTYPE html>
<html lang="en">

<head>

@if (App::environment('production'))
    <!-- HTML for production environment -->
    <!-- Basic Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Coaching - Wil Ng</title>
    <meta name="description" content="We help software engineers become exceptional leaders. From tech leads to CTOs, our coaching program empowers you to achieve happiness and impact.">

    <!-- Open Graph Meta Tags for social media sharing -->
    <meta property="og:title" content="Tech Coaching - Wil Ng">
    <meta property="og:description" content="We help software engineers become exceptional leaders. From tech leads to CTOs, our coaching program empowers you to achieve happiness and impact.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://greater.than.today">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Tech Coaching - Wil Ng">
    <meta name="twitter:description" content="We help software engineers become exceptional leaders. From tech leads to CTOs, our coaching program empowers you to achieve happiness and impact.">
    
    <!-- Keywords -->
    <meta name="keywords" content="tech leadership, software engineering coaching, tech leads, CTO coaching, engineering leadership, mental fitness, Agile SCRUM, conflict management, productivity, emotional intelligence, personal wellness">

    <!-- Author -->
    <meta name="author" content="Wil Ng">

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
    <nav class="navbar navbar-expand-lg navbar-light bg-white pb-3">
        <div class="container">
            <a class="navbar-brand" href="#">
               &nbsp; <!-- <img src="{{asset('images/logo.png')}}" class="image" alt=""> -->
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#7weeks">
                            Program
                            <span></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#meetyourcoach">
                            Meet your Coach
                            <span></span>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="/churches-and-charities">
                            For Charities and Churches
                            <span></span>
                        </a>
                    </li> -->
                    <!-- <li class="nav-item">
                        <a class="nav-link active" href="/tech-leads">
                            For Tech Leads
                            <span></span>
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-button" href="https://cal.com/wilng/tech-lead-coaching" target=”_blank”>Get
                            Started</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <section class="background--white">
            <div class="col-12 container">
                <div class="section-header-con">
                    <h1>
                        Why is building a successful career so difficult?
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
                        <h2 class="mb-4">Building a career is hard…
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
                        <a href="https://cal.com/wilng/tech-lead-coaching" target=”_blank” class="button px-4">Get
                            Started</a>
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

        <section class="background--blue">
            <div class="container">
                <div class="d-flex align-items-center row">
                    <div class="px-4 col-12 col-md-7">
                        <h2 class="mb-4 mb-lg-5"><b>
                                <div class="quote">“</div>
                                Thanks to Wil's guidance, I've felt more prepared to handle the
                                uncertainties that lie
                                ahead.”
                            </b></h2>
                        <p> — Tony Tong, CTO of The Mind Reader</p>
                    </div>
                    <div class="px-4 col-12 col-md-5">
                        <img src="{{asset('images/asset-4v2.png')}}" class="icon-image full" alt="">
                    </div>
                </div>
            </div>
        </section>
        <section id="7weeks" class="background--white">
            <div class="container">
                <div class="d-flex align-items-center row">
                    <div class="col-12">
                        <h2 class="mb-5">Let's get stronger together.</h2>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <h4 class="mb-3">Mental Fitness Program</h4>
                            <p>
                                Establishes an operating system for mental fitness.
                            </p>
                            <p>
                                This is critical for stress management, enhancing performance, emotional regulation, and
                                improving relationships.
                            </p>
                        </div>
                        <div class="col-md-5">
                            <h4 class="mb-3">1-on-1 Coaching Sessions</h4>
                            <p>
                                Weekly 1-hour coaching sessions focus on tailored goal setting to address your specific
                                needs and challenges.
                            </p>
                            <p>
                                Topics covered can include Agile SCRUM, conflict management, persuasion, productivity,
                                emotional intelligence, personal wellness, and coaching others.
                            </p>
                        </div>
                        <div class="col-md-3">
                            <img src="{{asset('images/asset-5v2.png')}}" class="icon-image full" alt="">
                            <div class="text-center">
                                <a href="https://cal.com/wilng/tech-lead-coaching" target=”_blank” class="button">Get
                                    Started</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="background--black">
            <div class="container text-center px-4">
                <h2>
                    “Success is the sum of small efforts, repeated day-in and day-out.”
                </h2>
                <h2 class="mt-3"><i>- Robert Collier</i></h2>
            </div>
        </section>
        <footer>
            <div class="container">
                <div class="legal-disclaimer py-5">
                    <div class="footer-header">Legal Notice & Disclaimers</div>
                    <p>&copy; 2025 PSALM12SEVEN PRIVATE LIMITED (202443598Z)</p>
                    
                    <p>PROGRAM CONTENT AND MATERIAL DO NOT CONSTITUTE MEDICAL OR MENTAL HEALTH ADVICE AND ARE NOT A
                        SUBSTITUTE
                        FOR PROFESSIONAL CARE, DIAGNOSIS, OR TREATMENT OF ANY MEDICAL OR MENTAL HEALTH CONDITION.
                    </p>
                    <div class="footer-header">DISCLAIMERS:</div>
                    <p>No reproduction, alteration, translation, publication, or distribution, in any form, printed
                        or
                        electronic, is permitted without the express prior wr   itten consent of PSALM12SEVEN PRIVATE
                        LIMITED.
                    </p>
                </div>
            </div>
        </footer>
    </main>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="{{asset('slick/slick.min.js')}}"></script>


    <script type="text/javascript">
    $('.section-slider').slick({
        arrows: false,
        dots: true,
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 3,
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

    $(document).ready(function() {
        $('.navbar-nav .nav-link').click(function(event) {
            $('.navbar-nav .nav-link').removeClass('active');
            $(this).addClass('active');
        });
    });
    </script>

</body>

</html>