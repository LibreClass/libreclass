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

  <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="/css/home.css">
  <link rel="stylesheet" type="text/css" href="/css/fa/css/font-awesome.min.css">

  <!-- Necessário para validação de formulários -->
  <link rel="stylesheet" type="text/css" href="/css/validation.css">

  <!-- Scripts are placed here -->
  <script type="text/javascript" src="/js/jquery.min.js"></script>
  <script type="text/javascript" src="/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="/js/register.js"></script>
  <script type="text/javascript" src="/js/menu.js"></script>

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

  @include('analytics')
</body>
</html>
