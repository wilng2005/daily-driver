<div class="nav-scroller py-1 mb-3 border-bottom">
    <nav class="nav nav-underline justify-content-between">
      @php
        use App\Models\Tag;
        $navs=Tag::where('published_at','<=',now())
            ->orderBy('sequence_code')
            ->get();
      @endphp

      @foreach($navs as $nav)
        <a @class([
            'nav-item',
            'nav-link', 
            'link-body-emphasis',
            'active' => isset($tag)?$tag->slug==$nav->slug:false,
        ]) href="/tag/{{$nav->slug}}">{{$nav->name}}</a>
      @endforeach
      <!-- <a class="nav-item nav-link link-body-emphasis" href="/about">About</a> -->
      <!-- <a class="nav-item nav-link link-body-emphasis active" href="#">Coaching</a> -->
       <!-- <a class="nav-item nav-link link-body-emphasis" href="/services">Services</a> -->
      <!-- <a class="nav-item nav-link link-body-emphasis" href="https://t.me/GreaterThanTodayBot">Clarity Chatbot</a>  -->
      <a class="nav-item nav-link link-body-emphasis" href="https://t.me/wilng2005" target="_blank">Contact us</a> 
    </nav>
</div>