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
    {{HTML::image(("http://s23.postimg.org/8imz07wyj/banner_svi.png"), null, ["style" => "width: 200px;"])}}
  
    <p style="font-size: 16pt">Seja bem vindo à nossa rede, {{ $name }}!</p>
  </center>

    <h4 style="font-size: 16pt">Falta pouco para você se tornar mais um membro da rede social educativa: LibreClass.</h4>
    
    <font style="font-size: 14pt; ">Nossos objetivos são:
    
    <ul>
        <li>Aumentar a produtividade do professor em sala de aula;</li>
        <li>Melhorar a comunicação entre professores, alunos e instituição;</li>
        <li>Reduzir a burocracia;</li>
        <li>Diminuir os gastos com papel.</li>
    </ul>
    </font>
    
    <font style="font-size: 12pt; "><p><a href="{{ $url }}"> Clique aqui para validar seu cadastro.</a></p></font>
</body>
</html>