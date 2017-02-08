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
              
              <h3 class="text-center"><i class="fa fa-close text-danger"></i> Erro</h3>
              <hr>
              <p>{{ $error }}</p>
              <br>
            
       
              <div class="pull-right">
                <a class="btn btn-default" href="http://localhost:6001">Ir para o início</a>
              </div>
              
            
              
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  
</body>
</html>