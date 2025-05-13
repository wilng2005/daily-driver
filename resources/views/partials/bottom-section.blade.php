<section id="7weeks" class="background--white">
    <div class="container">
        <div class="d-flex align-items-center row">
            <div class="col-12">
                <h2 class="mb-5">Let's get stronger together.</h2>
            </div>

            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h4 class="mb-3">Training Program</h4>
                    <p>
                        Establishes your base operating framework in mental fitness.
                    </p>
                    <p>
                        Get better at managing stress, enhancing performance, regulating emotions, and building stronger relationships.
                    </p>
                </div>
                <div class="col-md-5">
                    <h4 class="mb-3">Coaching Sessions</h4>
                    <p>
                        Each 1-hour coaching session focuses on tailored goal-setting to address your specific needs and challenges.
                    </p>
                    <p>
                        Gain clarity, lead better, influence with confidence, manage conflict, and grow in purpose, productivity, and emotional intelligence.
                    </p>
                </div>
                <div class="col-md-3">
                    <img src="{{asset('images/asset-5v2.png')}}" class="icon-image full" alt="">
                    <div class="text-center">
                        <a href="https://cal.com/wilng/tech-lead-coaching" target="_blank" class="button">Get
                            Started</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="insights-and-strategies" class="background--yellow" data-testid="insights-and-stories-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Insights and Strategies</h2>
            </div>
            <div class="section-slider">
            <div class="details-each">
                    <h4 class="mb-3">Resilient Leadership: Recovering from Mistakes and Setbacks</h4>
                    <p>
                        Mistakes are inevitable, but how you respond defines your resilience. Learn how to navigate setbacks, adapt, and emerge stronger.
                    </p>
                    <a href="{{ url('/article/resilient-leadership-recovering-from-mistakes-and-setbacks') }}" class="read-more">Read More &rarr;</a>
                   
                </div>
                <div class="details-each">
                    <h4 class="mb-3">Five Science-Backed Strategies to Recover from Burnout</h4>
                    <p>
                        Combating burnout requires more than rest alone; it involves actively completing the stress response cycle and replenishing...
                    </p>
                    <a href="{{ url('/article/five-science-backed-strategies') }}" class="read-more">Read More &rarr;</a>
                    <img src="{{asset('images/success-asset-1.png')}}" class="icon-image mb-3 ms-auto" alt="">
                </div>
            </div>
        </div>
    </div>
</section>
<section class="background--black">
    <div class="container text-center px-4">
        <h2>
            “The goal is not to be perfect by the end. 
        </h2>
        <h2>
            The goal is to be better today.”
        </h2>
        <h2 class="mt-3"><i>- Simon Sinek</i></h2>
    </div>
</section>
<footer>
    <div class="container">
        <div class="legal-disclaimer py-5">
            <div class="footer-header">Legal Notice & Disclaimers</div>
            <p>&copy; 2025 PSALM12SEVEN PRIVATE LIMITED (UEN: 202443598Z)</p>
            <p>PROGRAM CONTENT AND MATERIAL DO NOT CONSTITUTE MEDICAL OR MENTAL HEALTH ADVICE AND ARE NOT A
                SUBSTITUTE
                FOR PROFESSIONAL CARE, DIAGNOSIS, OR TREATMENT OF ANY MEDICAL OR MENTAL HEALTH CONDITION.
            </p>
            <div class="footer-header">DISCLAIMERS:</div>
            <p>No reproduction, alteration, translation, publication, or distribution, in any form, printed
                or
                electronic, is permitted without the express prior written consent of PSALM12SEVEN PRIVATE
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
