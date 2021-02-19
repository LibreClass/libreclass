@extends('social.master')

@section('css')
@parent
<link media="all" type="text/css" rel="stylesheet" href="/css/blocks.css">
@stop

@section('js')
@parent
<script src="/js/blocks.js"></script>
<script src="/js/avaliable.js"></script>
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
              <h3 class="text-blue"><i class="fa fa-file-text-o"></i> <b>RECUPERAÇÃO DA UNIDADE {{ $exam->unit->value }}</b></h3>
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
          <a href="{{ URL::to("/lectures/units?u=" . encrypt($exam->unit_id)) }}" class="btn btn-default btn-block">Voltar</a>
        </div>

      </div>
    </div>

@if( request()->has("edit") )
    <div id="block" class="block">

  {{ Form::open() }}

          <div class="row">
            <div class="col-md-8 col-xs-12">
              <div class="form-group">
                {{ Form::label("date-day", "Data da avaliação ")}}
                <div class="form-inline">
                  {{ Form::selectRange("date-day", 1, 31, (int) date("d", strtotime($exam->date)),["class" => "form-control"]) }}
                  {{ Form::selectRange("date-month", 1, 12, (int) date("m", strtotime($exam->date)), ["class" => "form-control"]) }}
                  {{ Form::selectRange("date-year", date("Y"), date("Y")-100, date("Y", strtotime($exam->date)), ["class" => "form-control"]) }}
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
                                        ], $exam->type, ["class" => "form-control", "required"]) }}
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                {{ Form::label("comment", "Comentários") }}
                {{ Form::textarea("comment", $exam->comments, ["class" => "form-control", "size" => "30x2"]) }}
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
@else
    <div id="block" class="block">
          <div class="row">
            <div class="col-md-8 col-xs-8">
              <div class="form-group">
              <h3>{{ $exam->title }}</h3>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-8 col-xs-8">
              <div class="form-group">
                <div class="form-inline">
                  Data da avaliação: {{ date("d / m / Y", strtotime($exam->date)) }}
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                Comentários: {{ $exam->comments }}
              </div>
            </div>
          </div>

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
                      <th>Nome</th>
                      <th>Média da Unidade</th>
                      <th>Final da Unidade</th>
                    </tr>
                  </thead>
                  <tbody id='{{ encrypt($exam->id) }}'>

  @foreach($attends as $attend )
    <?php $media = $attend->getUnit()->getAverage($attend->user_id) ?>
    @if(  $media[0] < $average)
                    <tr id='{{ encrypt($attend->id) }}'>
                      <td>{{ $attend->getUser()->name }}</td>
                      <td>{{ sprintf("%.2f", $media[0]) }}</td>
                      <td>
                        <div class="form-inline">
                          <div class="form-inside pull-left">
                            {{ Form::text("exam-value", $media[1], ["class" => "form-control-1x form-control exam-value", "data" => $media[1]]) }}
                            <i class="fa fa-undo form-inside-icon icon-default click back-avaliable"></i>
                            <i class="fa fa-send form-inside-icon text-info click submit-avaliable"></i>
                          </div>
                        <div class="pull-left feedback-response small"></div>
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
@endif


@stop
