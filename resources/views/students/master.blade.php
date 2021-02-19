<!doctype html>
<html lang="en">
<head>
  <title>
  @section('title')
    LibreClass Online
  @show
  </title>
  <link rel="shortcut icon" href="{{{asset("images/favicon.ico")}}}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <link media="all" type="text/css" rel="stylesheet" href="/css/bootstrap.min.css">
  <link media="all" type="text/css" rel="stylesheet" href="/css/social.master.css">
  <link media="all" type="text/css" rel="stylesheet" href="/css/students.css">

  <link media="all" type="text/css" rel="stylesheet" href="/css/fa/css/font-awesome.min.css">
  <link media="all" type="text/css" rel="stylesheet" href="/css/validation.css">

  @section('css')
  @show
  <script src="/js/jquery.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
  <script src="/js/validations/jquery.validate.min.js"></script>
  <script src="/js/validations/additional-methods.min.js"></script>

  @section('js')
  @show

</head>
<body>
  <div class="back">

  </div>

  <nav class="navbar navbar-libreclass navbar-fixed-top" role="navigation">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="fa fa-bars"></span>
        </button>
        <a class="navbar-brand" href="/"><img src="/images/icon-min.png" class="logo-min"></a>
      </div>

      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-left">
          <li><a href="/"><i class="fa fa-home fa-lg"></i> Home</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="/config"><i class="fa fa-user fa-lg"></i> Perfil</a></li>
          <li><a href="/logout"><i class="fa fa-sign-out fa-lg"></i> Sair</a></li>
        </ul>

      </div>

    </div>
  </nav>

  <div class="container-fluid navbar-fixed-margin">
      <div class="row">
        <div class="col-sm-3 col-md-2 col-xs-1 sidebar">
          <div class="user-info text-center hidden-xs">

            <div class="user-photo">
              {{-- HTML::image(strlen($user->photo) ? $user->photo : "/images/user-photo-default.jpg", '', array('class' => 'img-rounded center-block')) --}}
            </div>
            <div class="user-name">
              <span>{{-- $user->name --}}</span>
            </div>
            <a href='{{"/config"}}' >Editar Perfil</a>
          </div>

          <div class="sidebar-menu">
            @include("students.menu")
          </div>

          <footer class="sidebar-footer visible-lg visible-sm visible-md">
            <div class="text-center">
            <ul class="list-unstyled">
              <li id="report" class="click"><a><i class="fa fa-support"></i> Contate o suporte</a></li>
            </ul>

              <span ><i class="fa fa-linux"></i> {{ date('Y') }} - Sysvale SoftGroup</span>
            </div>

          </footer>

        </div>
        <div class="col-md-7 col-md-offset-2 col-xs-11 col-xs-offset-1 col-sm-6 col-sm-offset-3 content">

          @section('content')
          @show

        </div>

      </div>

  </div>
  <div class="visible-none">
  @include("modules.formTrash")
  @include("modules.formChangeStatus")
  </div>


  @include('messages.alert')
  @include('messages.confirm')
  @include('messages.error')
  @include('messages.success')
  @include('modules.report')
  @include('analytics')

</body>
</html>
