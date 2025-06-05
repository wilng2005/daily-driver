<section class="background--white">
            <div class="container">
            <div class="d-flex align-items-center row">
            <div class="col-12">
@php
    $bottomSectionMessages = [
        'Shift from firefighting to focused execution.',
        'Move from chaos to clarity and control.',
        'Shift from procrastination to action.',
        'Build momentum for your most important goals.',
        'Stop putting out fires—start making real progress.'
    ];
    $selectedBottomSectionMessage = $bottomSectionMessages[array_rand($bottomSectionMessages)];
@endphp
<h2 class="mb-5">{{ $selectedBottomSectionMessage }}</h2>
            </div>

            <div class="row">

                <div class="col-md-6">
                    <h4 class="mb-6">Free Discovery Session</h4>
                    <p>
                        Spend an hour focused on understanding your specific challenges, discovering your strengths, and establishing a path forward.
                    </p>
                    <p>
                        Take steps that shape the future, not just survive the day.
                    </p>
                    <a id="free-discovery-session-bottom" href="https://cal.com/wilng/free-coaching-session" target="_blank" class="read-more">Get Started &rarr;</a>
                </div>
                <div class="col-md-4">
                    <img src="{{asset('images/success-asset-2.png')}}" class="icon-image full" alt="">
                 
                </div>
            </div>
        </div>
    </div>
</section>

<section id="insights-and-strategies" class="background--blue" data-testid="insights-and-stories-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Insights and Strategies</h2>
            </div>
            <div class="section-slider">
                @foreach ($insights as $insight)
                <div class="details-each">
                    <h4 class="mb-3">{{ $insight->title }}</h4>
                    <p>
                    {{ $insight->description }}
                    </p>
                    <a href="{{ url('/insights/'.$insight->slug) }}" class="read-more">Read More &rarr;</a>
                    @if ($loop->index % 3 === 0 && $insight->image_path)
                        <img src="{{ asset($insight->image_path) }}" class="icon-image mb-3 ms-auto" alt="">
                    @endif
                </div>
                @endforeach
                <div class="details-each">
                    <h4 class="mb-3">New Job No Friends? You’re Not Alone</h4>
                    <p>
                    Starting your first tech job can feel isolating—but it doesn’t have to be. This guide shows you simple, proven ways to build real connections and feel like you belong.
                    </p>
                    <a href="{{ url('/article/new-job-no-friends-youre-not-alone') }}" class="read-more">Read More &rarr;</a>
                    <img src="{{asset('images/asset-3v2.png')}}" class="icon-image mb-3 ms-auto" alt="">
                </div>
                <div class="details-each">
                    <h4 class="mb-3">Exhausted and Empty? Maybe It’s Burnout</h4>
                    <p>
                    Feeling utterly drained at the end of each day? If you’re a busy entrepreneur, engineer, or manager running on fumes, you’re not alone – and it’s not a personal failing.
                    </p>
                    <a href="{{ url('/article/exhausted-and-empty-maybe-its-burnout') }}" class="read-more">Read More &rarr;</a>
                   
                </div>
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
<section id="7weeks" class="background--yellow">
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
                    <a id="free-discovery-session-bottom" href="https://cal.com/wilng/free-coaching-session" target="_blank" class="read-more">Get Started &rarr;</a>
                </div>
                <div class="col-md-5">
                    <h4 class="mb-3">Coaching Sessions</h4>
                    <p>
                        Each 1-hour coaching session focuses on tailored goal-setting to address your specific needs and challenges.
                    </p>
                    <p>
                        Gain clarity, lead better, influence with confidence, manage conflict, and grow in purpose, productivity, and emotional intelligence.
                    </p>
                    <a id="free-discovery-session-bottom" href="https://cal.com/wilng/free-coaching-session" target="_blank" class="read-more">Get Started &rarr;</a>
                </div>
                <div class="col-md-3">
                    <img src="{{asset('images/asset-5v2.png')}}" class="icon-image full" alt="">
      
                </div>
            </div>
        </div>
    </div>
</section>
<section class="background--black">
    <div class="container text-center px-4">
        <h2>
            Let's make tomorrow greater than today.
        </h2>
    </div>
</section>
<footer>
    <div class="container">

        <!-- Privacy Section -->
        <div class="legal-disclaimer py-5">
            <div class="footer-header">Privacy Statement</div>
            <p>We respect your privacy and do not collect personal information directly through this website.</p>

            <p>We use third-party tools such as <strong>Microsoft Clarity</strong> and <strong>Google Analytics</strong> to understand how visitors interact with our site. These tools may use cookies and session tracking to help us improve user experience. No personally identifiable information is collected or stored.</p>

            <p>If you book an appointment via <strong>Cal.com</strong>, your information is managed according to Cal.com’s privacy practices.</p>

            <p>By using this site, you consent to the use of these tools.</p>

            <p>Learn more from their official privacy policies:</p>
            <ul>
                <li><a href="https://privacy.microsoft.com/en-us/privacystatement" target="_blank" rel="noopener">Microsoft Clarity</a></li>
                <li><a href="https://support.google.com/analytics/answer/6004245" target="_blank" rel="noopener">Google Analytics</a></li>
                <li><a href="https://cal.com/privacy" target="_blank" rel="noopener">Cal.com</a></li>
            </ul>
        </div>

        <!-- Legal Section -->
        <div class="legal-disclaimer py-5">
            <div class="footer-header">Legal Notice & Disclaimers</div>
            <p>&copy; 2025 PSALM12SEVEN PRIVATE LIMITED (UEN: 202443598Z)</p>

            <p><strong>Disclaimer:</strong> The content and material on this site do not constitute medical or mental health advice and are not a substitute for professional care, diagnosis, or treatment.</p>

            <p>No reproduction, alteration, translation, publication, or distribution—whether printed or electronic—is permitted without prior written consent from PSALM12SEVEN PRIVATE LIMITED.</p>
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
