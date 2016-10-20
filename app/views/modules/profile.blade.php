@extends('social.master')

@section('css')
@parent
{{ HTML::style('css/blocks.css') }}
@stop

@section('js')
@parent
{{ HTML::script('js/blocks.js') }}
{{ HTML::script('js/student.js') }}
{{ HTML::script('js/validations/modulesAddStudents.js') }}
@stop

@section('body')
@parent

<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">
    
    <div class="block">
      <div class="row">
        <div class="col-sm-10">
          <h3 class="text-blue"><b><i class="fa fa-user"></i> Informações</b></h3>
        </div>
        <div class="col-sm-2 text-right">
          <a href="{{ URL::previous() }}" class="btn btn-block btn-default">Voltar</a>
        </div>
      </div>
    </div>
    
    <div id="block" class="block">
      
      <div class="row">
        <div class="col-xs-12">
          <h4>{{ $profile->name }}</h4><br>
          <p><b>Matrícula: </b> {{ $profile->enrollment }}</p>
          <p><b>Email: </b> {{ $profile->email == "" ? "Email não cadastrado" : $profile->email }}</p>
          <p><b>Data de Nascimento: </b> {{ date("d/m/Y", strtotime($profile->birthdate)) }}</p>
          <p><b>Sexo: </b> {{ $profile->gender == "F" ? "Feminino" : ($profile->gender == "M" ? "Masculino" : "Sexo não informado") }}</p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="visible-none">
{{ Form::open(["id" => "delete-discipline", "url" => url("/disciplines/delete")]) }}
  {{ Form::hidden("discipline", null) }}
{{ Form::close() }}
</div>

@stop
