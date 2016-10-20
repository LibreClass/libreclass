@extends('master')

@section('title')
@parent
Login
@stop

@section('extraJS')
@parent
  {{ HTML::script('http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/additional-methods.min.js') }}
  {{ HTML::script('http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js') }}
  {{ HTML::script('js/validations/usersLogin.js') }}
@stop

@section('body')
@parent

<div class="container w60">
  <br>

  <br>
  @if (Session::has("info"))
  <div class="alert alert-info text-center alert-dismissible" role="alert" >
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4>{{Session::get("info")}}</h4>
  </div>
  @endif
  @if (Session::has("error"))
  <div class="alert alert-danger text-center alert-dismissible" role="alert" >
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4>{{Session::get("error")}}</h4>
  </div>
  @endif

  
  <div id="form-login">
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
          {{ Form::open(["url" => url("/login"), "id" => "loginForm"]) }}
          <div class="col-md-12">
              <div class="form-group">
                {{ Form::text("email", null, ["placeholder" => "Digite seu email", "class" => "form-control input-lg", "required" => "required", "autofocus"]) }}
              </div>
              <div class="form-group">
                {{ Form::password("password", ["placeholder" => "Digite sua senha", "class" => "form-control input-lg", "required" => "required"]) }}
              </div>
          </div>
          <div class="col-md-12 col-xs-12">
            <button class="btn btn-shadow btn-primary btn-lg btn-block">Entrar</button>
          </div>
          <div class="col-md-12 col-xs-12 text-center">
            <ul class="list-inline">
              <li><a class="click" id="forgot-password"><b>Esqueci minha senha</b></a></li>
              <li><a href="{{ url('/') }}"><b>Cadastre-se</b></a></li>
            </ul>
          </div>
          {{ Form::close() }}
        </div>

        <br>
        <div class="row">
          <div class="col-md-12 text-center">
            <a id="btn-register-facebook" class="btn btn-shadow btn-facebook btn-lg btn-block-xs" href="{{ url('/fb') }}"><i class="fa fa-facebook fa-lg"></i>&nbsp;&nbsp;&nbsp;<b>Facebook</b></a>
            <a id="btn-register-google" class="btn btn-shadow btn-google btn-lg  btn-block-xs" href="{{ $google }}"><i class="fa fa-google-plus fa-lg"></i>&nbsp;&nbsp;&nbsp;<b>Google</b></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  

  <div id="forgot-password-area" class="visible-none">
    <div class="panel">
      <div class="panel-body">

        {{ Form::open(["url" => url("/forgot-password")]) }}
          <div class="row">
            <div class="col-md-12 text-center">
              <h3><b>Esqueceu a sua senha?</b></h3>
              <br>
              <p><b>Informe o seu email para recuperar o seu acesso ao LibreClass</b></p>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                {{ Form::text("email", isset($email) ? $email : null, ["placeholder" => "Digite seu email", "class" => "form-control input-lg", "required" => "required", "autofocus"]) }}
              </div>
              <button class="btn btn-primary btn-block btn-lg">Enviar</button>
            </div>
          </div>
        {{ Form::close() }}

      </div>
    </div>

  </div>
</div>

@stop
