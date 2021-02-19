@extends('master')

@section('title')
@parent
Login
@stop

@section('extraJS')
@parent
	{{-- <script type="text/javascript" src="/lib/additional-methods.min.js"></script> --}}
	<script type="text/javascript" src="/lib/jquery.validate.min.js"></script>
	<script type="text/javascript" src="/js/validations/usersLogin.js"></script>
@stop

@section('body')
@parent

<div class="container container-login">

	<img src="/images/logo-libreclass-vertical.png" class="logomarca center-block">
	<br>
	<h4 class="text-center">Faça o login na sua conta para acessar o Libreclass</h4>
	<br>
	@if (session("info"))
		<div class="alert alert-info text-center alert-dismissible" role="alert" >
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<span>{{ session("info") }}</span>
		</div>
	@endif
	@if (session("error"))
		<div class="alert alert-danger text-center alert-dismissible" role="alert" >
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<span>{{ session("error") }}</span>
		</div>
	@endif
	<div class="panel panel-login">
		<div class="panel-body">

			<div id="form-login">
				<div class="row">
					<form action="/login" method="post" id="loginForm">
						@csrf
						<div class="col-md-12">
								<div class="form-group">
									<input type="email" name="email" value="{{ old('email') }}" placeholder="Digite seu email" class="form-control" required autofocus>
								</div>
								<div class="form-group">
									<input type="password" name="password" placeholder="Digite sua senha" class="form-control" required>
								</div>
						</div>
						<div class="col-md-12 col-xs-12">
							<button class="btn btn-shadow btn-primary btn-block">Entrar</button>
						</div>
						<div class="col-md-12 col-xs-12 text-center text-xs form-help">
							<ul class="list-inline">
								<li class="checkbox">
									<input type="checkbox">Continuar conectado
								</li>
								<li><a class="click" id="forgot-password">Esqueci minha senha</a></li>
							</ul>
						</div>
					</form>
				</div>

				<hr>
				<div class="row">
					<div class="col-md-12 text-center">
						{{-- <a id="btn-register-facebook" class="btn btn-shadow btn-facebook btn-block-xs" href="{{ url('/fb') }}"><i class="fa fa-facebook fa-lg"></i>&nbsp;&nbsp;&nbsp;<b>Facebook</b></a> --}}
						{{-- <a id="btn-register-google" class="btn btn-shadow btn-google btn-block-xs" href="{{ $google }}"><i class="fa fa-google-plus fa-lg"></i>&nbsp;&nbsp;&nbsp;<b>Google</b></a> --}}
					</div>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-12 text-center text-xs">
					<a id="new-account" class="click">Criar nova conta</a>
				</div>
			</div>
		</div>
	</div>

</div>
@include('user.modalForgot')
@include('user.modalNewAccount')

@stop
