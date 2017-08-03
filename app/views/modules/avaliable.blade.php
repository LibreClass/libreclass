@extends('social.master')

@section('css')
@parent
{{ HTML::style('css/blocks.css') }}
@stop

@section('js')
@parent
{{ HTML::script('js/blocks.js') }}
{{ HTML::script('js/avaliable.js') }}
@stop

@section('body')
@parent


<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">
    <div class="block">
      <div class="row">
        <div class="col-md-10 col-sm-10">
          <div class="row">
            <div class="col-md-12">
              <h3 class="text-blue"><i class="fa fa-file-text-o"></i> <b>AVALIAÇÃO</b></h3>
            </div>
            <div class="col-md-12">
              <ol class="breadcrumb bg-white">
                <li>{{ $exam->unit->offer->classe->period->course->institution->name }}</li>
                <li>{{ $exam->unit->offer->classe->period->course->name }}</li>
                <li>{{ $exam->unit->offer->classe->period->name }}</li>
                <li>{{ $exam->unit->offer->getClass()->fullName() }}</li>
                <li class="active">{{ $exam->unit->offer->discipline->name }}</li>
              </ol>
            </div>
          </div>
        </div>
        <div class="col-md-2 col-sm-2 text-right">
          <a href='{{ URL::to("/lectures/units?u=" . Crypt::encrypt($exam->idUnit)) }}' class="btn btn-default btn-block">Voltar</a>
        </div>

      </div>
    </div>

    <div id="block" class="block">
{{ Form::open(["url" => URL::to("/avaliable/save")]) }}
      <div class="row">
        <div class="col-md-12 col-xs-12">
          <div class="form-group">
            {{ Form::label("title", "Título da Avaliação", ["class" => "control-label"]) }}
            <span class="help-block text-muted">Informe um título para sua avaliação.</span>
            {{ Form::text("title", $exam->title, ["class" => "form-control input-lg", "maxlength" => "255", "placeholder" => "Informe um título para disciplina", "required"]) }}
          </div>
        </div>
      </div>
@if( isset($exam->id) )
  {{ Form::hidden("exam", Crypt::encrypt($exam->id)) }}
@else
  {{ Form::hidden("unit", Crypt::encrypt($exam->idUnit)) }}
@endif
          <div class="row">
            <div class="col-md-8 col-xs-8">
              <div class="form-group">
                {{ Form::label("date-day", "Data da avaliação", ["class" => "control-label"])}}
                <span class="help-block text-muted">Informe a data da realização da avaliação.</span>
                <div class="form-inline">
                  {{ Form::selectRange("date-day", 1, 31, date("d", strtotime($exam->date)),["class" => "form-control input-lg"]) }}
                  {{ Form::selectRange("date-month", 1, 12, date("m", strtotime($exam->date)), ["class" => "form-control input-lg"]) }}
                  {{ Form::selectRange("date-year", date("Y"), date("Y")-100, date("Y", strtotime($exam->date)), ["class" => "form-control input-lg"]) }}
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                {{ Form::label("type", "Instrumento de Avaliação", ["class" => "control-label"]) }}
                <span class="help-block text-muted">Informe o instrumento de avaliação que será utilizado na avaliação.</span>
                {{ Form::select("type", [ "Prova Dissertativa Individual",
                                          "Prova Dissertativa em Grupo",
                                          "Prova Objetiva Individual",
                                          "Prova Objetiva em Grupo",
                                          "Trabalho Dissertativo Individual",
                                          "Trabalho Dissertativo em Grupo",
                                          "Apresentação de Seminário",
                                          "Projeto",
                                          "Produção Visual",
                                          "Pesquisa de Campo",
                                          "Texto Dissertativo",
                                          "Avaliação Prática",
                                          "Outros"
                                        ], $exam->type, ["class" => "form-control input-lg", "required"]) }}
              </div>
            </div>
          </div>
@if( $unit->calculation == "W" )
          <div class="row">
            <div class="col-md-12">
              <div class="form-group row col-md-4">
                {{ Form::label("weight", "Peso", ["class" => "control-label"]) }}
                <span class="help-block text-muted">Informe o peso da avaliação. A nota final será calculada utilizando o peso.</span>
                {{ Form::text("weight", $exam->weight, ["class" => "form-control input-lg", "maxlength" => "5"]) }}
              </div>
            </div>
          </div>
@endif

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                {{ Form::label("comment", "Comentários", ["class" => "control-label"]) }}
                <span class="help-block text-muted">Descreva sua avaliação. Este campo é opcional.</span>
                {{ Form::textarea("comment", $exam->comments, ["class" => "form-control input-lg", "size" => "30x3"]) }}
              </div>
            </div>
          </div>
          <div class="row">
              <div class="col-md-12 col-xs-12">
                <button class="btn btn-primary btn-lg pull-right"><i class="fa fa-save"></i> Salvar Avaliação</button>
              </div>
          </div>


{{ Form::close() }}
      </div>
<!--
    <div class="block">
      <div class="block-list">
        <div class="row">
          <div class="col-md-12">
            <h3><i class="fa fa-bar-chart"></i> Inserção de Notas</h3>
          </div>
        </div>
        <div class="block-list-item">
          <div class="row">
            <div class="col-md-12">

            {{ Form::open(["id" => "exam-form"]) }}

              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Nome</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id='{{ Crypt::encrypt($exam->id) }}'>

@foreach($students as $student )
                  <tr id='{{ Crypt::encrypt($student->id) }}'>
                    <td>{{ $student->getUser()->name }}</td>
                    <td>
                      <div class="form-inline">
                        {{ Form::text("exam-value", $student->getExamsValue($exam->id), ["class" => "form-control form-control-1x exam-value", "data" => $student->getExamsValue($exam->id)]) }}
                        <span class="icon-response"></span>
                      </div>
                    </td>
                  </tr>
@endforeach

                </tbody>
              </table>

              {{ Form::close() }}

            </div>
          </div>

          <br>
        </div>
      </div>
    </div>
    </div>
  </div>
</div>-->

@stop
