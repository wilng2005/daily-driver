<nav class="navbar navbar-expand-lg navbar-light bg-yellow pb-3">
        <div class="container">
            <a class="navbar-brand" href="#">
               &nbsp;  <img src="{{asset('images/logo-v4.png')}}" class="image" alt=""> 
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/insights">
                            Insights and Strategies
                            <span></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/#meetyourcoach">
                            Meet your Coach
                            <span></span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="/#testimonials">
                            Testimonials
                            <span></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pricing">
                            Programs &amp; Pricing
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
                        <a id="free-discovery-session-nav" class="nav-button" href="{{ url('/redirect-to-cal?target=https://cal.com/wilng/free-coaching-session') }}" target=”_blank”>Free Session</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>