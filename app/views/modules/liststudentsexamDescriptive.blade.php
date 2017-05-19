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
        <div class="col-sm-10">
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
        <div class="col-sm-2">
          <a href='{{ URL::to("/lectures/units?u=" . Crypt::encrypt($exam->idUnit)) }}' class="btn btn-default btn-block">Voltar</a>
        </div>

      </div>
    </div>

    <div id="block" class="block">
      <div class="row">
        <div class="col-md-12 col-xs-12">
            <h3>{{ $exam->title }}</h3>
        </div>
      </div>
      <hr>
      @if( isset($exam->id) )
        {{ Form::hidden("exam", Crypt::encrypt($exam->id)) }}
      @else
        {{ Form::hidden("unit", Crypt::encrypt($exam->idUnit)) }}
      @endif
      <div class="row">
        <div class="col-md-8 col-xs-8">
          <span class="text-info"><i class="fa fa-calendar"></i>{{ " ". date("d / m / Y", strtotime($exam->date)) }}</span>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <span><i class="fa fa-comment text-info"></i> {{ $exam->comments == "" ? "Não há comentários" : $exam->comments }} </span>
        </div>
      </div>
    </div>

    <div class="block">
      <div class="block-list">
        <div class="row">
          <div class="col-md-12">
            <h3 class="text-blue"><b><i class="fa fa-bar-chart"></i> Insersão de avaliações</b></h3>
          </div>
        </div>
        <div class="block-list-item">
          <div class="row">
            <div class="col-md-12">

              <!-- <ul class="list-inline">
                <li><i class="fa fa-undo icon-default"></i> Desfazer</li>
                <li><i class="fa fa-send text-info"></i> Salvar</li>
              </ul> -->

              {{ Form::open(["id" => "exam-form"]) }}

              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Nome</th>
                    <th>Parecer descritivo</th>
                    <th>Resultado</th>
                  </tr>
                </thead>
                <tbody id='{{ Crypt::encrypt($exam->id) }}'>

                  @foreach ($students as $key=>$student)
                  <tr id='{{ Crypt::encrypt($student->id) }}'>
                    <td>{{ $student->getUser()->name }}</td>
                    <td>
                      <div class="">
                        <div class="">
                          {{ Form::textarea('exam-description', $student->getDescriptiveExam($exam->id)["description"], ['size' => '45x5', 'maxlength' => 60000]) }}
                        </div>
                        <!-- <div class="pull-left feedback-response small"></div> -->
                      </div>
                    </td>
                    <td>
                      <div class="radio">
                        <label>
                          @if ($student->getDescriptiveExam($exam->id)["approved"] == 'A')
                            <input type="radio" class="approved" name="approved-{{ $key }}" value="A" checked>
                          @else
                            <input type="radio" class="approved" name="approved-{{ $key }}" value="A">
                          @endif
                            Aprovado
                        </label>
                      </div>
                      <div class="radio">
                        <label>
                          @if ($student->getDescriptiveExam($exam->id)["approved"] == 'R')
                            <input type="radio" class="approved" name="approved-{{ $key }}" value="R" checked>
                          @else
                            <input type="radio" class="approved" name="approved-{{ $key }}" value="R">
                          @endif
                          Reprovado
                        </label>
                      </div>
                      <div>
                        <button class="btn btn-default save-descriptive">Salvar</button>
                      </div>
                      <!-- <div class="save-descriptive">
                        <button class="btn btn-default">Salvando</button>
                      </div>
                      <div class="save-descriptive">
                        <button class="btn btn-success">Salvo!</button>
                      </div> -->
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
</div>

@stop
