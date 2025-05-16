@if (App::environment('production'))
<script async src="https://www.googletagmanager.com/gtag/js?id={{env('GA_ID')}}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '{{env('GA_ID')}}');
</script>
@endif
