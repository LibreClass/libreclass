<!DOCTYPE html>
<html>

<head>
  <title>
    @section('title')
      LibreClass Social
    @show
  </title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- CSS are placed here -->
  <link media="all" type="text/css" rel="stylesheet" href="/css/bootstrap.min.css">
  <link media="all" type="text/css" rel="stylesheet" href="/css/jquery-ui-1.9.2.custom.css">
  <link media="all" type="text/css" rel="stylesheet" href="/css/fa/css/font-awesome.min.css">
  <!-- Scripts are placed here -->
  <script src="/js/jquery.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
</head>

<body>

  <div id="page">

    <div id=main_menu class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">

        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/shop">Libreclass Beta</a>
        </div>

        <!-- Everything you want hidden at 940px or less, place within here -->
        <nav>
          <ul class="nav">
            <li><a href="/about">O que &eacute; o Libreclass</a></li>
            <li><a href="/terms">Termos de Uso</a></li>
            <li><a href="/privacy">Pol&iacute;tica de Privacidade</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            @section('logout')
            @show
          </ul>
        </nav>

      </div><!-- /.container -->
    </div><!-- /#main_menu -->

    <div id="header" align="center">
    </div><!-- /header -->

    @section('body')
    @show
    <div class="push" ></div>
    <div id="footer">
    </div><!-- /#footer -->
  </div><!-- /#page -->
  @include('analytics')
</body>
</html>
