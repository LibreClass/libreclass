<!doctype html>
<html lang="en">
<head>
  <title>
    Libreclass Offline
  </title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no ">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  {{ HTML::style('css/bootstrap.min.css') }}
  {{ HTML::style('css/home.css') }}
  {{ HTML::style('css/fa/css/font-awesome.min.css') }}
  <!-- Scripts are placed here -->
</head>
<body>

  <div class="container w100 header">
    <div class="container w40">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <a href="{{"/"}}">{{ HTML::image("images/logolibreclass.png", null, ["class" => "logo center-block img-responsive"])}}</a>
          </div>
        </div>
    </div>
    <br>
    <br>
    <div class="container w40">
      <div class="row">
        <div class="col-md-12">
          <div class="panel">
            <div class="panel-body">

              {{ HTML::image(strlen($data->photo) ? $data->photo : "/images/user-photo-default.jpg", '', array('class' => 'img-user-sm img-rounded center-block')) }}
              <div class="text-center">
                <h3>{{ $data->name }}</h3>
                <p>{{ $data->email }}</p>
              </div>
              <br>

              <h3 class="text-center">Este aplicativo requer permissões especiais.</h3>
              <hr>
              <div class="media">
                <div class="pull-left">
                  <i class="fa fa-lg fa-user text-info"></i>
                </div>
                <div class="media-body">
                  <h4 class="text-info"><b>Dados do Usuário</b></h4>
                  <span>Usa informações de conta e dados do perfil.</span>
                </div>
              </div>

              <div class="media">
                <div class="pull-left">
                  <i class="fa fa-lg fa-users text-info"></i>
                </div>
                <div class="media-body">
                  <h4 class="text-info"><b>Lista de Alunos</b></h4>
                  <span>Obtém informações sobre os alunos vinculados à sua conta.</span>
                </div>
              </div>

              <div class="media">
                <div class="pull-left">
                  <i class="fa fa-lg fa-list text-info"></i>
                </div>
                <div class="media-body">
                  <h4 class="text-info"><b>Lista de Disciplinas</b></h4>
                  <span>Obtém informações sobre as disciplinas vinculadas à você.</span>
                </div>
              </div>
              <br>
              <hr>

            {{ Form::open(["id" => "form-data", "url" => Session::get("lb")]) }}
              {{ Form::hidden("data", $data) }}
              <div class="pull-left">
                <a href="#" class="text-muted">Termos do Aplicativo</a>
              </div>
              <div class="pull-right">
                <a class="btn btn-default" href="{{ Session::get("lb") }}">Cancelar</a>
                {{ Form::submit("Aceito",["class"=>"btn btn-primary"]) }}
              </div>

            {{ Form::close() }}

          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

</body>
</html>