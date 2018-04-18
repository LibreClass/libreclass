<!doctype html>
<html lang="en">
<head>
  <title>
    Libreclass Online
  @section('title')
  @show
  </title>
  <link rel="shortcut icon" href="{{{asset("images/favicon.ico")}}}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no ">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  {{ HTML::style('css/bootstrap.min.css') }}
  {{ HTML::style('css/home.css') }}
  {{-- HTML::style('css/jquery-ui-1.9.2.custom.css') --}}
  {{ HTML::style('css/fa/css/font-awesome.min.css') }}

  <!-- Necessário para validação de formulários -->
  {{ HTML::style('css/validation.css') }}

  <!-- Scripts are placed here -->
  {{ HTML::script('js/jquery.min.js', ["asyn defer"]) }}
  {{ HTML::script('js/bootstrap.min.js', ["asyn defer"]) }}
  {{ HTML::script('js/register.js', ["asyn defer"]) }}
  {{ HTML::script('js/menu.js', ["asyn defer"]) }}

  <script type="text/javascript">
    if (navigator.userAgent.match(/msie/i) || navigator.userAgent.match(/trident/i) ){
      window.location.href("/ie");
    }
  </script>

  @section('extraJS')
  @show

</head>
<body>
  @section('body')
  @show

  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-90938637-1', 'auto');
    ga('send', 'pageview');
  </script>
</body>
</html>
