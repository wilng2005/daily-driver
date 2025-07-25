<section id="pricing" class="background--yellow">
    <div class="container">
        <div class="d-flex align-items-center row">
            <div class="col-12">
                <h2 class="mb-5">Programs &amp; Pricing</h2>
            </div>

            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h4 class="mb-3">Free Trial</h4>
                    <p>
                        Discover if this coaching relationship is right for you.
                    </p>
                    <p>
                        What's included:
                    </p>
                    <ul>
                        <li>Review of work, self-care and relationships</li>
                        <li>Strategies to navigate current challenges</li>
                        <li>Clear, actionable steps to move forward</li>
                    </ul>
                    <p>
                        Pricing
                    </p>
                    <ul>
                        <li>Free for one month!</li>
                        
                    </ul>
                   
                    <a id="free-discovery-session-bottom" href="{{ url('/redirect-to-cal?target=https://cal.com/wilng/free-coaching-session') }}" target="_blank" class="read-more">Get Started &rarr;</a>
                </div>
                <div class="col-md-5">
                    <h4 class="mb-3">Unlimited Coaching Program</h4>
                    <p>
                        Gain clarity, lead better, influence with confidence.
                    </p>
                    <p>
                        What's included:
                    </p>
                    <ul>
                        <li>Unlimited 1-to-1 virtual coaching sessions*</li>
                        <li>App guided daily practices to support your goals</li>
                        <li>Weekly training videos with measurable progress</li>
                    </ul>
                    <em>* Terms and conditions apply</em>
                    <br/><br/>
                    <p>
                        Pricing
                    </p>
                    <ul>
                        <li>Monthly: SGD$240</li>
                        <li>Annual: SGD$2400 <em>(that's two months free!)</em></li>
                    </ul>
                    <a id="free-discovery-session-bottom" href="{{ url('/redirect-to-cal?target=https://cal.com/wilng/free-coaching-session') }}" target="_blank" class="read-more">Book Now &rarr;</a>
                </div>
                <div class="col-md-3">
                    <img src="{{asset('images/asset-5v2.png')}}" class="icon-image full" alt="">
      
                </div>
            </div>
        </div>
    </div>
</section>

<section class="background--blue">
            <div class="container">
            <div class="d-flex align-items-center row">
            <div class="col-12">
@php
    $bottomSectionMessages = [
        'Shift from firefighting to focused execution.',
        'Move from chaos to clarity and control.',
        'Shift from procrastination to action.',
        'Build momentum for your most important goals.',
        'Stop putting out fires and start making real progress.'
    ];
    $selectedBottomSectionMessage = $bottomSectionMessages[array_rand($bottomSectionMessages)];
@endphp
<h2 class="mb-5">{{ $selectedBottomSectionMessage }}</h2>
            </div>

            <div class="row">

                <div class="col-md-6">
                    <h4 class="mb-6">Free Discovery Session</h4>
                    <p>
                        In just one hour, uncover what’s holding you back, tap into your strengths, and chart a clear, actionable path forward.
                    </p>
                    <p>
                        Take steps that shape the future, not just survive the day.
                    </p>
                    <a id="free-discovery-session-bottom" href="{{ url('/redirect-to-cal?target=https://cal.com/wilng/free-coaching-session') }}" target="_blank" class="read-more">Book Now &rarr;</a>
                </div>
                <div class="col-md-4">
                    <img src="{{asset('images/success-asset-2.png')}}" class="icon-image full" alt="">
                 
                </div>
            </div>
        </div>
    </div>
</section>

<section id="insights-and-strategies" class="background--white" data-testid="insights-and-stories-section">
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
                    @if ($loop->index % 3 === 2 && $insight->image_filename)
                        <img src="{{ asset('images/'.$insight->image_filename) }}" class="icon-image mb-3 ms-auto" alt="">
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

<section class="background--black">
    <div class="container text-center px-4">
        <h2>
            Let's make tomorrow greater than today.
        </h2>
        <div class="d-flex justify-content-center align-items-center gap-4 mt-3" aria-label="Social Media Links">
            <a href="https://www.youtube.com/@greaterthantoday/" target="_blank" rel="noopener" aria-label="YouTube" class="text-white social-link fs-2">
                <i class="bi bi-youtube"></i>
            </a>
            <a href="https://open.spotify.com/show/0BcUomCr0OurTp0zM6a8e5?si=O_V7Om9tRXuJ3Dyg87wqKA" target="_blank" rel="noopener" aria-label="Spotify" class="text-white social-link fs-2">
                <i class="bi bi-spotify"></i>
            </a>
            <a href="https://www.tiktok.com/@wil.ng.2005" target="_blank" rel="noopener" aria-label="TikTok" class="text-white social-link fs-2">
                <!-- TikTok is not in Bootstrap Icons; using SVG fallback -->
                <svg width="1.5em" height="1.5em" viewBox="0 0 48 48" fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;"><path d="M41.5 15.5c-3.6 0-6.5-2.9-6.5-6.5h-5v23c0 2.5-2 4.5-4.5 4.5s-4.5-2-4.5-4.5 2-4.5 4.5-4.5c.5 0 1 .1 1.5.2v-5.1c-.5-.1-1-.1-1.5-.1-5.2 0-9.5 4.3-9.5 9.5s4.3 9.5 9.5 9.5 9.5-4.3 9.5-9.5V20.6c1.9 1.2 4.1 1.9 6.5 1.9v-7z"/></svg>
            </a>
            <a href="https://www.linkedin.com/in/wilng2005/" target="_blank" rel="noopener" aria-label="LinkedIn" class="text-white social-link fs-2">
                <i class="bi bi-linkedin"></i>
            </a>
        </div>
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
