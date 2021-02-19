
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/images/favicon.ico">

    <title>LibreClass</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
  </head>

  <style type="text/css" media="screen">
    img {
      max-width: 50%;
      min-width: 200px;
      margin: 10% auto 0;
    }
    body {
      background-color: #FEFEFE;
      background-image: url('/img/back.png');
    }
  </style>

  <body>

    <!-- Begin page content -->
    <div class="container">
      <div class="row">
        <div class="col-md-6 col-md-offset-3 col-xs-12 text-center">
          <img src="/images/icon.png" alt="LibreClass Logo">
        </div>
      </div>
      <div class="page-header">
        <h1>Atenção</h1>
        <p class="lead">Não foi possível gerar o relatório!</p>
      </div>
      <div class="alert alert-info lead">
        {{ $message }}
      </div>
    </div>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
    @include('analytics')
  </body>
</html>
