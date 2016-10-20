@extends('master')

@section('title')
@parent
:: Cadastre-se
@stop


@section('body')
@parent
<br><br><br><br>


<div class="container container-register">
  <div class="row">
    <div class="col-md-6 col-sm-6">
      <h2 class="text-position">Cadastre-se</h2>
      <div class="push"></div>
      {{ Form::open(["id" => "new-register"]) }}
        <div class="form-group">
          {{ Form::label("name", "Nome") }}
          {{ Form::text("name", null, ["placeholder" => "Nome fantasia da empresa", "class" => "form-control", "required" => "required"]) }}
        </div>
        <div class="form-group">
          {{ Form::label("email") }}
          {{ Form::text("email", null, ["placeholder" => "Digite o email da sua empresa", "class" => "form-control", "required" => "required"]) }}
        </div>
        <div class="form-group has-feedback">
          {{ Form::label("password", "Senha") }}
          {{ Form::password("password", ["placeholder" => "Digite sua senha", "class" => "form-control", "required" => "required"]) }}
          <span id="eye-magic" class="glyphicon glyphicon-eye-open form-control-feedback click text-muted" ></span>
        </div>
        <div class="form-group">
          {{ Form::button("Cadastrar", ["type" => "submit", "class" => "btn btn-go"]) }}
        </div>
      {{ Form::close() }}
    </div>
  </div>
</div>

@stop
