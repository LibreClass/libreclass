@extends('social.master')

@section('css')
@parent
{{ HTML::style('css/blocks.css') }}
@stop

@section('js')
@parent
{{ HTML::script('js/blocks.js') }}
{{ HTML::script('js/lessons.js') }}
@stop

@section('body')
@parent


<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">
    <div id="block" class="block">

      <div class="row">
        <div class="col-md-10 col-sm-10">
          <div class="row">
            <div class="col-md-12">
              <h3 class="text-blue"><i class="fa fa-file-text-o"></i> <b>AULA</b></h3>
            </div>
            <div class="col-md-12">
              <ol class="breadcrumb bg-white">
                <li>{{ $lesson->unit->offer->classe->period->course->institution->name }}</li>
                <li>{{ $lesson->unit->offer->classe->period->course->name }}</li>
                <li>{{ $lesson->unit->offer->classe->period->name }}</li>
                <li>{{ $lesson->unit->offer->getClass()->fullName() }}</li>
                <li class="active">{{ $lesson->unit->offer->discipline->name }}</li>
              </ol>
            </div>
          </div>
        </div>
        <div class="col-md-2 col-sm-2 text-right">
          <a href="{{ URL::to("/lectures/units?u=" . Crypt::encrypt($lesson->idUnit)) }}" class="btn btn-default btn-block">Voltar</a>
        </div>

      </div>
      <div class="block-list">
        <div class="block-list-item">
          {{ Form::open(["url" => URL::to("/lessons/save?l=" . Crypt::encrypt($lesson->id) )]) }}

          <div class="row">
            <div class="col-sm-8 col-xs-12">
              {{ Form::label("date-day", "Data da aula: ", ["class" => "control-label"])}}
              <span class="help-block text-muted">Informe a data de realização da aula.</span>
            <div class="form-inline">
              <div class="form-group">

                {{ Form::selectRange("date-day", 1, 31, date("d", strtotime($lesson->date)),["class" => "form-control"]) }}
                {{ Form::selectRange("date-month", 1, 12, date("m", strtotime($lesson->date)), ["class" => "form-control"]) }}
                {{ Form::selectRange("date-year", date("Y"), date("Y")-100, date("Y", strtotime($lesson->date)), ["class" => "form-control"]) }}
              </div>
            </div>
            </div>
          </div>
          <br>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                {{ Form::label("title", "Titulo da aula") }}
                <span class="help-block text-muted">Escolha um título para sua aula.</span>
                {{ Form::text("title", $lesson->title, ["class" => "form-control", "maxlength" => "255"]) }}
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <a id="insert-plan" class="click text-link"><h4><i class="fa fa-plus"></i> Inserir Planejamento</h4></a>
            </div>
          </div>
          <div id="plan-lesson" class="visible-none">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  {{ Form::label("description", "Descrição") }}
                  <span class="help-block text-muted">Informe um resumo que descreva sua aula.</span>
                  {{ Form::textarea("description", $lesson->description, ["class" => "form-control", "size" => "30x4"]) }}
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  {{ Form::label("goals", "Objetivos") }}
                  <span class="help-block text-muted">Neste campo devem ser apresentados os objetivos a serem alcançados na aula</span>
                  {{ Form::textarea("goals", $lesson->goals, ["class" => "form-control", "size" => "30x2"]) }}
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  {{ Form::label("content", "Conteúdo") }}
                  <span class="help-block text-muted">Neste campo devem ser apresentados os conteúdos programados para a aula.</span>
                  {{ Form::textarea("content", $lesson->content, ["class" => "form-control", "size" => "30x2"]) }}
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  {{ Form::label("methodology", "Metodologia") }}
                  <span class="help-block text-muted">Neste campo devem ser apresentados os procedimentos e sequências das atividades a serem realizadas na aula.</span>
                  {{ Form::textarea("methodology", $lesson->methodology, ["class" => "form-control", "size" => "30x4"]) }}
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  {{ Form::label("resources", "Recursos Necessários") }}
                  <span class="help-block text-muted">Neste campo devem ser apresentados os recursos a serem utilizados para o desenvolvimento da aula.</span>
                  {{ Form::textarea("resources", $lesson->resources, ["class" => "form-control", "size" => "30x4"]) }}
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  {{ Form::label("valuation", "Avaliação") }}
                  <span class="help-block text-muted">Informe neste campo a forma e os critérios de avaliação.</span>
                  {{ Form::text("valuation", $lesson->valuation, ["class" => "form-control", "maxlength" => "255"]) }}
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                  {{ Form::label("estimatedTime", "Tempo de aula") }}
                  <span class="help-block text-muted">Informe o tempo da aula em minutos.</span>
                  {{ Form::input("number", "estimatedTime", $lesson->estimatedTime, ["class" => "form-control", "min" => "0"]) }}
                </div>
              </div>
              <div class="col-md-8 col-sm-12 col-xs-12">
                <div class="form-group">
                  {{ Form::label("keyworks", "Palavras-chave") }}
                  <span class="help-block text-muted">Palavras chave ajudam a identificar a sua aula, quando são listadas por outros usuários.</span>
                  {{ Form::text("keyworks", $lesson->keyworks, ["class" => "form-control", "maxlength" => "255"]) }}
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                  {{ Form::label("bibliography", "Bibliografia") }}
                  <span class="help-block text-muted">Informe fontes literárias, sites, livros, autores ou outras referências bibliográficas utilizadas para sua aula.</span>
                  {{ Form::text("bibliography", $lesson->bibliography, ["class" => "form-control", "maxlength" => "255"]) }}
                </div>
              </div>
            </div>

          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <a class="click text-link" id="insert-notes"><h4><i class="fa fa-plus"></i> Anotações de Aula</h4></a>
            </div>
          </div>

          <div id="notes-lesson" class="visible-none">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  {{ Form::label("notes", "Anotações") }}
                  <span class="help-block text-muted">As anotações facilitam a revisão do conteúdo e descrevem os acontecimentos da aula.</span>
                  {{ Form::textarea("notes", $lesson->notes, ["class" => "form-control", "size" => "30x5"]) }}
                </div>
              </div>
            </div>
          </div>

          <button class="btn btn-primary btn-lg pull-right"><i class="fa fa-save"></i> Salvar Aula</button>

          <br>


          {{ Form::close() }}

        </div>
        <br>
        <div class="block-list-item">
          <div class="row">
            <div class="col-md-12">
              <h4 class="text-link">Frequência de Aula</h4>
            </div>
          </div>

          @if( $students == null )
          <h4 class="text-center">Não há alunos cadastrados na unidade</h4>
          @else
          <div class="row">
            <div class="col-md-12">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Nome</th>
                    <th></th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id='{{ Crypt::encrypt($lesson->id) }}'>
                  @foreach($students as $student )
                    <tr id='{{ Crypt::encrypt($student->idAttend) }}'>
                      <td>{{ $student->name }}</td>
                      <td><button class='btn change-frequency pull-right {{ $student->value == "P" ? "btn-success" : "btn-danger" }} center-block'>{{ $student->value }}</button></td>
                      <td>{{ sprintf("%d (%.1f %%)", $student->qtd, 100.*$student->qtd/$student->maxlessons) }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          @endif

        </div>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
// function disableselect(e) {
//   return false;
// }
//
// document.onselectstart = new Function ("return false");
// if (window.sidebar) {
//   document.onmousedown = disableselect;
//   document.onclick = reEnable;
// }
</script>

@stop

