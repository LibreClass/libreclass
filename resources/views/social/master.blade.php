<!doctype html>
<html lang="en">

<head>
  <title>
    @section('title')
    LibreClass Online
    @show
  </title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="/images/favicon.ico">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="/css/social.master.css">
  <link rel="stylesheet" type="text/css" href="/css/icon-education.css">
  <link rel="stylesheet" type="text/css" href="/css/fa/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="/css/validation.css">
  <link rel="stylesheet" type="text/css" href="/assets/dialogs.min.css">

  @section('css')
  @show
  <!-- Scripts are placed here -->
  <script type="text/javascript" src="/js/jquery.min.js"></script>
  <script type="text/javascript" src="/assets/dialogs.min.js"></script>
  <script type="text/javascript" src="/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="/js/validations/jquery.validate.min.js"></script>
  <script type="text/javascript" src="/js/validations/additional-methods.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
  <script src="/js/teacher.js"></script>
  @section('js')
  @show

</head>

<body>
<div id="app">
  <div class="back"></div>
  <nav class="navbar navbar-libreclass navbar-fixed-top" role="navigation">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
          aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="fa fa-bars"></span>
        </button>
        <a class="navbar-brand" href="/">
          <img src="/images/icon-min.png" class="logo-min">
        </a>
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
            <img src="{{ auth()->user()->photo ?? "/images/user-photo-default.jpg" }}" class="img-rounded center-block">
          </div>
          <div class="user-name">
            <span>{{ auth()->user()->name }}</span>
          </div>
          <a href="/config">Editar Perfil</a>
        </div>

        <div class="sidebar-menu">
          @include("social.menu")
        </div>

        <footer class="sidebar-footer visible-lg visible-sm visible-md">
          <div class="text-center">
            <ul class="list-unstyled">
              <li id="report" class="click"><a><i class="fa fa-support"></i> Contate o suporte</a></li>
            </ul>
            <span><i class="fa fa-linux"></i> {{ date('Y') }} - LibreClass</span>
          </div>
        </footer>
      </div>
      <div class="col-md-10 col-md-offset-2 col-xs-11 col-xs-offset-1 col-sm-9 col-sm-offset-3 content">
        @section('body')
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


  </div>
<script src="{{mix('js/app.js')}}"></script>

</body>

</html>