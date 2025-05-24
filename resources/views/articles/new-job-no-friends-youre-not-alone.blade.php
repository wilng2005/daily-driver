<!DOCTYPE html>
<html lang="en">

<head>
@include('partials.gtm-head')

@if (App::environment('production'))
    <!-- HTML for production environment -->
    @include('partials.ga-tag')
    <!-- Basic Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Job No Friends? You’re Not Alone</title>
    <meta name="description" content="Starting your first tech job can feel isolating—but it doesn’t have to be. This guide shows you simple, proven ways to build real connections and feel like you belong.">

    <!-- Open Graph Meta Tags for social media sharing -->
    <meta property="og:title" content="New Job No Friends? You’re Not Alone">
    <meta property="og:description" content="Starting your first tech job can feel isolating—but it doesn’t have to be. This guide shows you simple, proven ways to build real connections and feel like you belong.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://greater.than.today">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="New Job No Friends? You’re Not Alone">
    <meta name="twitter:description" content="Starting your first tech job can feel isolating—but it doesn’t have to be. This guide shows you simple, proven ways to build real connections and feel like you belong.">
    
    <!-- Keywords -->
    <meta name="keywords" content="new job, first tech job, isolation, connections, belonging, remote, imposter syndrome">

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
    <title>New Job, No Friends? You're Not Alone.</title>

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
    @include('partials.gtm-body')
    @include('partials.nav')

    <main>


  
       
        <section id="content-section-1" class="background--white">
            <div class="container">
                <div class="d-flex align-items-center row">
                    <div class="col-md-7 pe-5">
                        <div>
                        <h1>New Job No Friends? You're Not Alone</h1>
                        <br/>
  <p>If you’ve just landed your first job in tech, you might be excited but also overwhelmed. There’s a lot to learn fast—new systems, new jargon, new people. It’s totally normal to feel uncertain, anxious, or even isolated. You might find yourself wondering if it’s okay to ask a question or if people will judge your ideas. That feeling? It’s often called <em>imposter syndrome</em>.</p>
  <p>Studies show that <strong>role clarity</strong>, <strong>confidence in your skills</strong> (self-efficacy), and especially a <strong>sense of belonging</strong> are what help newcomers adjust best.</p>
  <p>If you don’t feel socially connected at work, you’re not likely to speak up in meetings or reach out when you’re stuck. And you’re not alone. Many new hires start optimistic but quickly feel less confident and more hesitant to contribute.</p>


                        </div>
                    </div>
                <div class="col-md-5">
                        <img src="{{asset('images/bored-01.png')}}" class="icon-image full" alt="">
                    </div> 
                </div>
            </div>
        </section>
        <section id="content-section-2" class="background--yellow">
            <div class="container">
                <div class="d-flex align-items-center row">
                <div class="col-md-5">
                        <img src="{{asset('images/awkward-01.png')}}" class="icon-image full" alt="">
                    </div> 
                    <div class="col-md-7 pe-5">
                        <div>
                        <h2>Why It Can Be Harder to Connect</h2>
                        <br/>
  <p>Things like your age, background, or whether you're working remotely can make a big difference in how quickly you connect. If you're older, you may bring life experience but feel out of sync with younger teammates or unfamiliar tools. If you're very junior, you may pick up tech fast but find it harder to read workplace etiquette. Cultural differences add another layer: what’s normal in one country or team might be odd in another.</p>
  <p>Working from home? That can make things trickier. Without casual hallway chats or lunch outings, it takes more effort to feel like part of the team. You might miss out on small, spontaneous interactions that build trust.</p>

                        </div>
                    </div>
             
                </div>
            </div>
        </section>
        <section id="content-section-3" class="background--white">
            <div class="container">
                <div class="d-flex align-items-center row">
                    <div class="col-md-7 pe-5">
                        <div>
                        <h2>What You Can Do to Build Real Connections</h2>
<br/>
<h3>1. Start with Small Conversations</h3>
<p>You don’t need to be super social. Small moments matter. Say hi in Slack, comment on someone’s setup, or ask about a tool they use. These <strong>micro-interactions</strong> add up and make you more visible in a natural way. Try:</p>
<ul>
  <li>“That’s a cool terminal theme. What are you using?”</li>
  <li>“Any tips on this feature in Figma? Still finding my feet.”</li>
</ul>

<h3>2. Attend Shared Activities</h3>
<p>Join team lunches, game nights, or Slack interest channels—even virtual ones. Don’t wait to be invited. Casual settings lower the pressure and let you meet teammates as people, not just colleagues. Some companies even have weekly "Watercooler" breaks or hobby groups. It’s a great way to feel like part of the crew.</p>

<h3>3. Use or Ask for a Buddy or Mentor</h3>
<p>If your company offers a buddy or mentor program, use it! These are the people who can answer questions you might feel weird asking your boss. If there’s no program, ask a friendly teammate to be your go-to person for a few weeks. It helps a lot.</p>



                        </div>
                    </div>
                    <div class="col-md-5">
                        <img src="{{asset('images/mentor-01.png')}}" class="icon-image full" alt="">
                    </div> 
                </div>
            </div>
        </section>
      
        <section id="content-section-4" class="background--blue">
            <div class="container">
                <div class="d-flex align-items-center row">
                <div class="col-md-5">
                        <img src="{{asset('images/friends-01.png')}}" class="icon-image full" alt="">
                    </div> 
                    <div class="col-md-7 pe-5">
                        <div>
                        <h2>Leverage Culture and Team Norms</h2>
                        <br/>

<h3>4. Help Create Psychological Safety</h3>
<p>Speak up, even a little. Admit when you’re unsure. Ask for feedback. When you model that kind of openness, it encourages others to do the same—and that builds a team where people trust each other. Leaders who show vulnerability (e.g., "I’m still learning that too") create space for juniors to thrive.</p>

<h3>5. Learn the Culture (and Use It to Connect)</h3>
<p>Pay attention to how your company works: how people celebrate wins, share feedback, or welcome newcomers. These cultural clues tell you how to behave. Participating in group rituals or Slack threads helps you go from outsider to insider.</p>
<p>If your company has employee groups (e.g., women in tech, international teammates), join one. It’s a fast track to meeting others who may share your experiences or background.</p>

<h3>6. Ask for Structure If You Need It</h3>
<p>Don’t hesitate to request a short meeting with your manager to clarify expectations or meet more teammates. Structured onboarding, like scheduled 1-on-1s or intro guides, makes a huge difference in how connected you feel.</p>

                        </div>
                    </div>
 
                </div>
            </div>
        </section>
        <section id="content-section-5" class="background--yellow">
            <div class="container">
                <div class="d-flex align-items-center row">
                    <div class="col-md-7 pe-5">
                        <div>
                        <h2>Why Relationships Matter for Performance and Well-being</h2><br/>
  <p>Building relationships now doesn’t just make work nicer—it helps your career. People who feel connected are more productive, stick around longer, and avoid burnout. Good onboarding, especially with social support, leads to better performance and higher job satisfaction.</p>

  <p>Without strong relationships, new hires are more likely to quit early. In fact, turnover is highest in the first year—often because people never felt like they belonged.</p>

  <p>Feeling part of a community also protects your mental health. It boosts confidence and reduces stress. The more connected you feel, the more you’ll grow.</p>
  <h3>References</h3>
  <ul>
     <li><a href="https://onlinelibrary.wiley.com/doi/full/10.1002/hrm.22041" target="_blank">[15] Human Resource Management – Remote Work and Integration</a></li>
    <li><a href="https://psycnet.apa.org/doiLanding?doi=10.1037%2Fapl0000412" target="_blank">[19] Journal of Applied Psychology – Belonging and Adjustment</a></li>
  
    <li><a href="https://journals.sagepub.com/doi/abs/10.1177/0149206305279602" target="_blank">[30] Journal of Management – Cultural Fit and Socialization</a></li>

  </ul>

                        </div>
                    </div>
                    <div class="col-md-5">
                        <img src="{{asset('images/asset-9.png')}}" class="icon-image full" alt="">
                    </div> 
                </div>
            </div>
        </section>
     
        @include('partials.bottom-section')
                                

</html>