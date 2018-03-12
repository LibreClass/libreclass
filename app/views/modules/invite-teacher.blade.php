@extends('social.master')

@section('css')
@parent
{{ HTML::style('css/blocks.css') }}
@stop

@section('js')
@parent
{{ HTML::script('js/blocks.js') }}
{{ HTML::script('js/teacher.js') }}
{{ HTML::script('js/validations/modulesAddTeachers.js') }}
@stop

@section('body')
@parent

<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">
    <div id="block" class="block">

      <div class="row">
        <div class="col-md-6 col-xs-6">
          <h4 >MEUS PROFESSORES</h4>
        </div>
        <div class="col-md-6 col-xs-6">
          <button id="new-block" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Novo Professor</button>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="block-list">

            <div class="row block-list-item">
              
            {{ Form::open() }}
            {{ Form::hidden("teacher", null) }}
            <div class="form-group">
              {{ Form::label("name", "*Nome") }}
              <span class="help-block text-muted">Informe o nome do professor.</span>
              {{ Form::text("name", null, ["class" => "form-control", "autofocus", "required"]) }}
            </div>
            <div class="form-group">
              {{ Form::label("email", "Email") }}
              <span class="help-block text-muted">Informe um email do professor. Deve ser um email válido.</span>
              {{ Form::email("email", null, ["class" => "form-control"]) }}
            </div>
            <div class="form-group">
              {{ Form::label("course", "Curso") }}
              <span class="help-block text-muted">Escolha o curso que o professor está vinculado.</span>
              {{ Form::select("course", $courses, null, ["class" => "form-control"]) }}
            </div>

            {{ Form::submit("Confirmar", ["class" => "btn btn-primary"]) }}
          {{ Form::close() }}
              
            </div>
          </div>
        </div>

      </div>
    </div>

@stop
