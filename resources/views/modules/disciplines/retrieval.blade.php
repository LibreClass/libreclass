@extends('social.master')

@section('css')
@parent
<link media="all" type="text/css" rel="stylesheet" href="/css/blocks.css">
@stop

@section('js')
@parent
<script src="/js/blocks.js"></script>
<script src="/js/avaliablefinal.js"></script>
@stop

@section('body')
@parent


<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">
    <div class="block">
      <div class="row">
        <div class="col-sm-10 col-xs-12">
          <div class="row">
            <div class="col-md-12">
              <h3 class="text-blue"><i class="fa fa-file-text-o"></i> <b>RECUPERAÇÃO FINAL</b></h3>
            </div>
            <div class="col-md-12">
              <ol class="breadcrumb bg-white">
                <li>{{ $offer->classe->period->course->institution->name }}</li>
                <li>{{ $offer->classe->period->course->name }}</li>
                <li>{{ $offer->classe->period->name }}</li>
                <li>{{ $offer->getClass()->fullName() }}</li>
                <li class="active">{{ $offer->discipline->name }}</li>
              </ol>
            </div>
          </div>
        </div>
        <div class="col-sm-2 col-xs-12 text-right">
          <a href='{{ URL::to("lectures") }}' class="btn btn-default btn-block btn-block-xs">Voltar</a>
        </div>

      </div>
    </div>

    <div id="block" class="block">
{{ Form::open() }}

          <div class="row">
            <div class="col-md-8 col-xs-12">
              <div class="form-group">
                <div class="form-inline">

                  {{ Form::label("date-day", "Data da avaliação ")}}
                  {{ Form::selectRange("date-day", 1, 31, (int) date("d", strtotime($offer->date_final)),["class" => "form-control"]) }}
                  {{ Form::selectRange("date-month", 1, 12, (int) date("m", strtotime($offer->date_final)), ["class" => "form-control"]) }}
                  {{ Form::selectRange("date-year", date("Y"), date("Y")-10, date("Y", strtotime($offer->date_final)), ["class" => "form-control"]) }}
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                {{ Form::label("type", "Instrumento de Avaliação") }}
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
                                        ], $offer->type_final, ["class" => "form-control", "required"]) }}
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                {{ Form::label("comment", "Comentários") }}
                {{ Form::textarea("comment", $offer->comments, ["class" => "form-control", "size" => "30x2"]) }}
              </div>
            </div>
          </div>


          <div class="row">
              <div class="col-md-12 col-xs-12">
                <button class="btn btn-primary"><i class="fa fa-save"></i> Salvar</button>
              </div>
          </div>


</form>
      </div>

    <div class="block">


          <div class="row">
            <div class="col-md-12">
              <h3><i class="fa fa-bar-chart"></i> Inserção de Notas</h3>
            </div>
          </div>
          <div class="block-list-item">
            <div class="row">
              <div class="col-md-12">
              <ul class="list-inline">
                <li><i class="fa fa-undo icon-default"></i> Desfazer</li>
                <li><i class="fa fa-send text-info"></i> Salvar</li>
              </ul>

              {{ Form::open(["id" => "exam-form"]) }}
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th style="width: 60%;">Nome</th>
                      <th>Média</th>
                      <th>Nota Final</th>
                    </tr>
                  </thead>
                  <tbody id='{{ encrypt($offer->id) }}'>

  @foreach($alunos as $aluno)
    @if($aluno->med < $course->average)
                    <tr id='{{ encrypt($aluno->id) }}'>
                      <td>{{ $aluno->name }}</td>
                      <td>{{ sprintf("%.2f", $aluno->med ) }}</td>
                      <td>
                        <div class="form-inline">
                          <div class="form-inside pull-left">
                            {{ Form::text("exam-value", $aluno->final, ["class" => "form-control-1x form-control exam-value", "data" => $aluno->final]) }}
                            <i class="fa fa-undo form-inside-icon icon-default click back-avaliable"></i>
                            <i class="fa fa-send form-inside-icon text-info click submit-avaliable"></i>
                          </div>
                          <div class="pull-left feedback-response small"></span>
                        </div>
                      </td>
                    </tr>
    @endif
  @endforeach

                  </tbody>
                </table>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

@stop
