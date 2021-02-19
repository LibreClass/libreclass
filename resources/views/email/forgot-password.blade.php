<html>

<head>
  <style>
    ul li {
      margin: 5px;
    }
  </style>
</head>

<body>
  <center>
    <img src="{{ url('/images/libreclass.png') }}" style="width: 200px;">

    <p style="font-size: 16pt">Olá, {{ $user->name }}!</p>
  </center>
    <br>
    <p style="font-size: 16pt">Geramos uma senha de acesso temporária para você!</p>
    <p style="font-size: 16pt">Para manter segura a sua conta, acesse seu Perfil e altere sua senha temporária</p>

    <h3>Dados de acesso:</h3>
    <font style="font-size: 12pt; ">

    <p><b>Página de Login: </b><a href="{{ url('/login') }}">libreclass.com/login</a></p>
    <p><b>Email: </b>{{ $user->email }}</p>
    <p><b>Nova Senha: </b>{{ $password }}</p>
    </font>
</body>
</html>