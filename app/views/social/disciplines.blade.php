@extends('social.master')

@section('css')
@parent
{{ HTML::style('css/blocks.css') }}
@stop

@section('js')
@parent
{{ HTML::script('js/blocks.js') }}
{{ HTML::script('js/disciplines.js') }}
{{ HTML::script('js/validations/socialDisciplines.js') }}
@stop



@section('body')
@parent

@if(!$listCourses)
<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">

    <div class="block">
      <div class="row">
        <div class="col-sm-12 col-xs-12">
          <h3 class="text-blue"><i class="fa fa-list-ul"></i> <b>Minhas Disciplinas</b></h3>
        </div>
      </div>
    </div>
    <div class="block">
      <h3 class="text-blue"><b>Você não possui cursos cadastrados.</b></h3>
      <p>Para cadastrar uma nova disciplina é preciso ter pelo menos um curso cadastrado.</p>
      <br>
      <a href="/courses" class="text-md"><i class="fa fa-folder-o"></i> Cadastrar um novo curso</a>
    </div>
          
    </div>
</div>
@else

<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">
    <div id="block">
      <div class="block">
        <div class="row">
          <div class="col-md-6 col-xs-12">
            <h3 class="text-blue"><i class="fa fa-list-ul"></i> <b>Minhas Disciplinas</b></h3>
          </div>
          <div class="col-md-6 col-xs-12 text-right">
            <button id="new-block" class="btn btn-primary btn-block-xs"><b><i class="fa fa-plus"></i> Nova disciplina</b></button>
          </div>
        </div>
      </div>
      
      <div class="block">
        <div class="row">
          <div class="col-md-12">
            {{ Form::open(["id" => "select-course"]) }}
              <div class="form-group">
                {{ Form::label("course", "Curso", ["class" => "control-label text-md"]) }}
                <span class="help-block text-muted">Selecione o curso para visualizar as disciplinas.</span>
                {{ Form::select("course", $listCourses, null, ["class" => "form-control input-lg"]) }}
              </div>
            {{ Form::close() }}
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="block-list">

              <div class="row block-list-item">
                <div class="col-md-12"></div>
                <div class="col-md-12">
                  <div id="list-disciplines">

                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="block-add" class="block visible-none">
      <div class="row">
        <div class="col-md-6 col-xs-6">
          <h4 id="title-discipline">Nova Disciplina</h4>
        </div>
        <div class="col-md-6 col-xs-6">
          <button id="btn-back" class="btn btn-default pull-right">Voltar</button>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          {{ Form::open(["id" => "new-discipline", "url" => URL::to("/disciplines/save") ]) }}
            {{ Form::hidden("discipline", null) }}
            <div class="form-group">
              {{ Form::label("course", "Curso", ["class" => "control-label"]) }}
               <span class="help-block text-muted">Selecione o curso para qual deseja cadastrar a disciplina.</span>
              {{ Form::select("course", $listCourses, null, ["class" => "form-control input-lg"]) }}
            </div>
            <div class="form-group">
              {{ Form::label("period", "Série/Período", ["class" => "control-label"]) }}
              <span class="help-block text-muted">Selecione a série ou período para o qual deseja vincular a disciplina</span>
              {{ Form::select("period", [""], null, ["class" => "form-control input-lg"]) }}
              {{-- Form::text("period", null, ["class" => "form-control input-lg", "autofocus"]) --}}
            </div>
            <div class="form-group">
              {{ Form::label("name", "Nome", ["class" => "control-label"]) }}
              <span class="help-block text-muted">Escolha um título que identifique a disciplina</span>
              {{ Form::text("name", null, ["class" => "form-control input-lg"]) }}
            </div>
            <div class="form-group">
              {{ Form::label("ementa", "Ementa", ["class" => "control-label"]) }}
              <span class="help-block text-muted">A ementa descreve os conteúdos abordados pela disciplina.</span>
              {{ Form::textarea("ementa", null, ["class" => "form-control input-lg"]) }}
            </div>

            {{ Form::submit("Salvar", ["class" => "pull-right btn btn-lg btn-primary"]) }}
          {{ Form::close() }}


        </div>
      </div>
    </div>
  </div>
@endif
@stop
