

<html>
<head>
  <link media="all" type="text/css" rel="stylesheet" href="/css/bootstrap.min.css">
  <link media="all" type="text/css" rel="stylesheet" href="/css/question.css">
  <script src="/js/jquery.min.js"></script>
  <title>
    LibreClass
  </title>

  <script>
    $(document).ready(function(){
      $("form").submit(function(){
        //~ alert( "[{{ $key }}] " + $("*[name='{{ $key }}']").val() );
        var aux = {};
        for ( var i = 0 ; i < $("select, input").length ; i++ )
          aux[$("select, input").eq(i).attr("name")] = $("select, input").eq(i).val();

//         alert(aux["birthdate-day"]);

        $.post($(this).attr("action"), aux,
        function(data){
          alert(data);
//           if ( data == "error" )
//             alert("Campo inválido");
//           else
//             location.reload();

        });
        return false;
      });
    });
  </script>
</head>
<body>

  <div class="container w40 text-white">
    <div class="push2x"></div>
    <div class="push2x"></div>
    <div class="push2x"></div>
    <div class="row">
      <div class="col-md-12">
        <h1 class="text-center"> Bem vindo ao LibreClass</h1>
        <div class="push2x"></div>
        <h3 class=" text-center question-title">Você está perto de uma experiência educacional inovadora....</h3>
        <div class="push1x"></div>
        <div class="text-center text-white">
          {{ ""; $key = "photo" }}


          @if ($key == "photo")
            {{ Form::open(["url" => url("config/photo"), "enctype" => "multipart/form-data"]) }}
              <div class="form-group" style="margin-bottom: 5px;">
                {{ Form::label("photo", "Foto") }}
                {{ Form::file("photo", ["class" => "form-control photo-button"]) }}
              </div>
              {{ Form::submit("Atualizar", ["class"=>"btn btn-primary"]) }}
            </form>

          @elseif($key == "name")
            {{ Form::open(["url" => url("config/common")]) }}
              <div class="form-group">
                {{ Form::label("name", "Nome") }}
                {{ Form::text("name", null, ["class" => "form-control"]) }}
              </div>
              {{ Form::submit("Atualizar", ["class"=>"btn btn-primary"]) }}
            </form>

          @elseif($key == "birthdate")
            {{ Form::open(["url" => url("config/birthdate")]) }}
              <div class="form-group">
                {{ Form::label("birthdate-day", "Data de Nascimento") }}
                {{ Form::selectRange("birthdate-day", 1, 31, null,["class" => "form-control"]) }}
                {{ Form::selectRange("birthdate-month", 1, 12, null, ["class" => "form-control"]) }}
                {{ Form::selectRange("birthdate-year", date("Y"), date("Y")-100, null, ["class" => "form-control"]) }}
              </div>
              {{ Form::submit("Atualizar", ["class"=>"btn btn-primary"]) }}
            </form>

          @elseif($key == "gender")
            {{ Form::open(["url" => url("config/commonselect")]) }}
              <div class="form-group">
                {{ Form::label("gender", "Sexo") }}
                {{ Form::select("gender", null, $user->gender, ["class" => "form-control"]) }}
              </div>
              {{ Form::submit("Atualizar", ["class"=>"btn btn-primary"]) }}
            </form>

          @elseif($key == "formation")
            {{ Form::open(["url" => url("config/commonselect")]) }}
              <div class="form-group">
                {{ Form::label("formation", "Formação") }}
                {{ Form::select("formation", $select["formation"], null, ["class" => "form-control"]) }}
              </div>
              {{ Form::submit("Atualizar", ["class"=>"btn btn-primary"]) }}
            </form>

          @elseif($key == "course")
            {{ Form::open(["url" => url("config/common")]) }}
              <div class="form-group">
                {{ Form::label("course", "Curso") }}
                {{ Form::text("course", null, ["class" => "form-control"]) }}
              </div>
              {{ Form::submit("Atualizar", ["class"=>"btn btn-primary"]) }}
            </form>

          @elseif($key == "institution")
            {{ Form::open(["url" => url("config/common")]) }}
              <div class="form-group">
                {{ Form::label("institution", "Instituição") }}
                { Form::text("institution", null, ["class" => "form-control"]) }}
              </div>
              {{ Form::submit("Atualizar", ["class"=>"btn btn-primary"]) }}
            </form>

          @elseif($key == "type")
            {{ Form::open(["url" => url("config/type")]) }}
              <div class="form-group">
                {{ Form::label("type", "Tipo de Conta") }}
                { Form::select("type", $select["type"], null, ["class" => "form-control"]) }}
              </div>
              {{ Form::submit("Atualizar", ["class"=>"btn btn-primary"]) }}
            </form>

          @else
            {{ date("Y-m-d H:m:s") }}
          @endif

        </div>

      </div>
    </div>
  </div>
  @include('analytics')

</body>
</html>


