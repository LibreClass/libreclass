@extends('master')
@section('extraJS')
@parent
  {{ HTML::script('http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/additional-methods.min.js', ["asyn defer"]) }}
  {{ HTML::script('http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js', ["asyn defer"]) }}
  {{ HTML::script('js/validations/home.js', ["asyn defer"]) }}
@stop

@section('body')
@parent

<div class="push2x"></div>
<div class="row">
  <div class="col-md-12">

    <div id="block-register-option" class="text-center">

      @if (Session::has("msg"))
        <div class="container w60 alert alert-info text-center"><h4>{{Session::get("msg")}}</h4></div>
      @endif
      <br>
      <a href="{{ url("/login") }}" class="btn btn-shadow btn-primary btn-lg btn-fx-300"><i class="fa fa-sign-in fa-lg"></i> <b>Entrar</b></a><br><br>
      <a id="btn-register-facebook" class="btn btn-shadow btn-facebook btn-lg btn-fx-300" href="{{ url('/fb') }}"><i class="fa fa-facebook fa-lg"></i> <b>Cadastrar com Facebook</b></a><br><br>
      <a id="btn-register-google" class="btn btn-shadow btn-google btn-lg btn-fx-300" href="{{ $google }}"><i class="fa fa-google-plus fa-lg"></i><b>Cadastrar com Google+</b></a><br><br>
      <div id="btn-register-email" class="btn btn-shadow btn-default btn-lg btn-fx-300"><i class="fa fa-envelope-o fa-lg"></i> <b>Cadastrar com Email</b></div>
      <div class="push1x"></div>
    </div>
    <br>
    <div id="block-register-email" class="container w60 visible-none">
      <div class="panel panel-default">
        <div class="panel-body">
          {{ Form::open(["url" => url("/")]) }}
            <div class="form-group">
              {{ Form::label("name", "Nome", ["class" => ""] ) }}
              {{ Form::text("name", null, [ "placeholder" => "Nome", "class" => "form-control input-lg", "required" ])}}
            </div>
            <div class="form-group">
              {{ Form::label("email",null,  ["class" => " text-left"]) }}
              {{ Form::email("email", null, [ "placeholder" => "Email", "class" => "form-control input-lg","required" ])}}
            </div>

            <div class="form-group has-feedback">
              {{ Form::label("password", "Senha", ["class" => " text-left"]) }}
              {{ Form::password("password", [ "placeholder" => "Senha", "class" => "form-control input-lg", "required" ])}}
              <span id="eye-magic" class="glyphicon glyphicon-eye-open form-control-feedback click text-muted" ></span>
            </div>
            <div class="text-center">
            <button class="btn btn-primary btn-lg btn-block">Cadastrar</button>
            {{ Form::close() }}
            <div class="text-center">
              <div class="push1x"></div>
              <span class="register-back click">Voltar</span>
            </div>
        </div>
      </div>
      
    </div>
  </div>
</div>

@stop
