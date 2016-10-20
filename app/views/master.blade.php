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

  @section('extraJS')
  @show

</head>
<body>
  
  <div class="container w100 header">
    <div class="container w60">
        <div class="row">
<!--          <div class="col-md-6 col-sm-6 col-xs-6">
            <a href="{{"/"}}">{{ HTML::image("images/logolibreclass.png", null, ["class" => "logo img-responsive"])}}</a>
          </div>-->
       
            <img class="center-block img-logo" src="images/icon.png">           
          

           @section('body')
           @show
  
          
                    
        </div>
 
    </div>
  </div>
  
</body>
</html>
