<!doctype html>
<html lang="en">
<head>
  <title>
    LibreClass Offline
  </title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no ">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <link media="all" type="text/css" rel="stylesheet" href="/css/bootstrap.min.css">
  <link media="all" type="text/css" rel="stylesheet" href="/css/home.css">
  <link media="all" type="text/css" rel="stylesheet" href="/css/fa/css/font-awesome.min.css">
  <script src="/js/jquery.min.js"></script>
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

              <h3 class="text-center"><i class="fa fa-cloud-upload text-info"></i> Sincronizar dados</h3>

              <hr>
              <p>Ao clicar em <b>confirmar</b>, os dados do LibreClass Online serão atualizados com as informações do aplicativo offline.</p>
              <p>Isso significa que o servidor online ficará igual ao offline.</p>
              <p>Essa ação é irreversível.</p>
              <br>

              <div id="form-send">
                {{ Form::open(["id" => "form-data",  "method" => "get"]) }}
                  {{ Form::hidden("confirm", "confirm") }}

                  <div class="pull-right">
                    <a class="btn btn-default" href="http://localhost:6001">Cancelar</a>
                    {{ Form::submit("Confirmar",["class"=>"btn btn-primary", "id" => "form-data-submit"]) }}
                  </div>

                </form>
              </div>
              <div id="spin-send" class="text-center visible-none">
                <i class="fa fa-spinner fa-spin fa-2x text-blue"></i><br>
                <span class='text-muted'>Aguarde. Estamos sincronizando.</span>
              </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

  <script>

      $("#form-data-submit").click(function(){
        $("#form-send").hide();
        $("#spin-send").show();
      });
  </script>
  @include('analytics')
</body>
</html>
