@extends('social.master')

@section('css')
@parent
{{ HTML::style('css/blocks.css') }}
@stop

@section('js')
@parent
{{ HTML::script('js/blocks.js') }}
{{ HTML::script('js/classes.js') }}
{{ HTML::script('js/validations/modulesClasses.js') }}
@stop

@section('body')
@parent

<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">

    <div id="block">
      <div class="block">
        <div class="row">
          <div class="col-md-6 col-xs-12">
            <h3 class="text-blue"><i class="icon-classes"></i> <b>Minhas Turmas</b></h3>
          </div>
          <div class="col-md-6 col-xs-12 text-right">
            <button id="new-block" class="btn btn-primary btn-block-xs"><i class="fa fa-plus"></i><b> Nova turma</b></button>
          </div>
        </div>
      </div>
      
      <div class="block">
        <div class="row">
          <div class="col-md-12">
          @if( $classes == null )
          <div class="text-md text-center">Não há turmas cadastradas</div>
          @else
            <table class="table table-hover table-striped">
              <thead>
                <tr>
                  <th><span class="text-md">Turma</span></th>
                  <th><span class="text-md">Curso</span></th>
                  <th><span class="text-md">Período</span></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach( $classes as $classe )
                  @if( $classe->status == 'E' )
                  <tr class="data" key="{{Crypt::encrypt($classe->id)}}">
                    <td><a class="btn btn-default" href='{{ URL::to("classes/offers?t=".Crypt::encrypt($classe->id)) }}'>{{ $classe->classe }}</a></td>
                    <td>{{ $classe->name }}</td>
                    <td>{{ $classe->period }}</td>
                    <td>
                      <div class="col-md-12">
                        <i class="pull-right fa fa-gears icon-default click" data-toggle="dropdown" aria-expanded="false"></i>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                          <li><a class="classe-edit"><i class="fa fa-edit text-primary"></i> Editar</a></li>
                          <li><a class="change-status click" href="/classes/change-status" value="B"><i class="fa fa-times-circle" style="color: red;"></i> Bloquear</a></li>
                          <li><a class="trash click"><i class="fa fa-trash text-danger"></i> Deletar</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                  @endif
                @endforeach

                @foreach( $classes as $classe )
                  @if( $classe->status == 'B' )
                  <tr class="data danger" key="{{Crypt::encrypt($classe->id)}}">
                    <td><a class="btn btn-default" href='{{ URL::to("classes/offers?t=".Crypt::encrypt($classe->id)) }}'>{{ $classe->classe }}</a></td>
                    <td>{{ $classe->name }}</td>
                    <td>{{ $classe->period }}</td>
                    <td>
                      <div class="col-md-12">
                        <i class="pull-right fa fa-gears icon-default click" data-toggle="dropdown" aria-expanded="false"></i>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                          <li><a class="classe-edit"><i class="fa fa-edit text-primary"></i> Editar</a></li>
                          <li><a class="change-status click" href="/classes/change-status" value="E"><i class="fa fa-times-circle" style="color: blue;"></i> Ativar</a></li>
                          <li><a class="trash click"><i class="fa fa-trash text-danger"></i> Deletar</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                  @endif
                @endforeach

              </tbody>
            </table>
          @endif
          </div>
        </div>
      </div>
    </div>

    <div id="block-add" class="block visible-none">
      <div class="row">
        <div class="col-sm-10">
          <h3 id="title-classe">Nova Turma</h3>
        </div>
        <div class="col-sm-2">
          <button id="btn-back" class="btn btn-default btn-block btn-block-xs">Voltar</button>
        </div>
      </div>
      <br>
      {{ Form::open([ "url" => URL::to("classes/new"), "id" => "form-classe"]) }}
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="form-group">
            {{ Form::label("period", "Curso/Período") }}
            <span class="help-block text-muted">Selecione o curso e período para a turma</span>
            {{ Form::select("period", $listPeriod, null, ["class" => "form-control input-lg"]) }}
          </div>
        </div>

      </div>
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-6">
          <div class="form-group">
            {{ Form::label("class", "Nome da turma", ["class" => "control-label"]) }}
            <span class="help-block text-muted">Informe um nome para identificar a turma. Ex: 2015.1.</span>
            {{ Form::text("class", null, ["class" => "form-control input-lg"]) }}
          </div>
        </div>

      </div>
      <div class="row">
        <div class="col-md-12">
          <div id="block-offer-discipline"></div>
          <br>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          {{ Form::submit("Salvar", ["class" => "pull-right btn btn-lg btn-primary"]) }}
          {{ Form::close() }}
        </div>
      </div>
    </div>
  </div>
</div>
@stop
