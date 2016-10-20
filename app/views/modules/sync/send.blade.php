<!doctype html>
<html lang="en">
<head>
  <title>
    LibreClass Offline
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

              {{-- Informações do usuário --}}

              {{ HTML::image(strlen($data->photo) ? $data->photo : "/images/user-photo-default.jpg", '', array('class' => 'img-user-sm img-rounded center-block')) }}
              <div class="text-center">
                <h3>{{ $data->name }}</h3>
                <p>{{ $data->email }}</p>
              </div>
              <br>

              <h3 class="text-center"><i class="fa fa-cloud-upload text-info"></i> Enviar dados</h3>
              <hr>
              <p>Ao clicar em <b>confirmar</b>, os dados do LibreClass Online serão atualizados com as informações do aplicativo offline.</p>
              <p>Isso significa que o servidor online ficará igual ao offline.</p>
              <p>Essa ação é irreversível.</p>
              <br>
            {{ Form::open(["id" => "form-data",  "method" => "get"]) }}
              {{ Form::hidden("confirm", "confirm") }}

              <div class="pull-right">
                <a class="btn btn-default" href="http://libreclass-offline.app:8080">Cancelar</a>
                {{ Form::submit("Confirmar",["class"=>"btn btn-primary"]) }}
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