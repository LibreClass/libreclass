@extends('social.master')

@section('css')
@parent
{{ HTML::style('css/blocks.css') }}
{{ HTML::style('css/forms.css') }}
@stop

@section('js')
@parent
{{ HTML::script('js/blocks.js') }}
{{ HTML::script('js/lessons.js') }}
{{ HTML::script('js/units.js') }}
@stop

@section('body')
@parent



  


<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">
    <div class="block">
      
    
      <div class="row">
        <div class="col-sm-10">
          <ol class="breadcrumb bg-white">
            <li>{{ $unit_current->getOffer()->getDiscipline()->getPeriod()->getCourse()->getInstitution()->name }}</li>
            <li>{{ $unit_current->getOffer()->getDiscipline()->getPeriod()->getCourse()->name }}</li>
            <li>{{ $unit_current->getOffer()->getDiscipline()->getPeriod()->name }}</li>
            <li class="active">{{ $unit_current->getOffer()->getClass()->class }}</li>
          </ol>
        </div>
        <div class="col-sm-2 text-right">
          <a href='{{ URL::to("lectures") }}' class="btn btn-default btn-block">Voltar</a>
        </div>    
      </div>
    </div>
    
    <div id="block-title" class="block">
      <div class="row">
        <div class="col-md-12">
          <h4 class="pull-left text-link">{{ $unit_current->getOffer()->getDiscipline()->name }}</h4>
          

        </div>
      </div>
      <div class="row">
  <div class="col-md-12">
    <div class="alert alert-info" id="message" hidden>
    </div>
  </div>
</div>

@if($unit_current->status == "E")
      <div class="row">
        <div class="col-md-2 col-sm-3">
          <div class="form-group">
            {{ Form::label("unit", "Unidade") }}
            {{-- Form::select("unit", $list_units, $unit_current->value-1, ["class" => "form-control", "disabled" => "disabled"]) --}}
            <h4>{{ $unit_current->value }}</h4>
          </div>
        </div>
        <div class="col-md-3 col-sm-3">
          <div class="form-group">
            {{ Form::open(["id" => "form-calculation"]) }}
              {{ Form::hidden("unit", Crypt::encrypt($unit_current->id)) }}
              {{ Form::label("calculation", "Cálculo da Média") }}
              {{ Form::select("calculation", ["S" => "Soma", "A" => "Média Aritmética", "W" => "Média Ponderada"], $unit_current->calculation, ["class" => "form-control"]) }}
            {{ Form::close() }}
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <ul class="list-inline">
            <li>
              <a href='{{ URL::to("/lectures/units/student?u=" . Crypt::encrypt($unit_current->id)) }}' class="btn btn-primary">Gerenciar Alunos</a>
            </li>
            <li>
              <a href='{{ URL::to("/avaliable/finalunit/" . Crypt::encrypt($unit_current->id)) . "?edit=e" }}' class="btn btn-primary">Recuperação da Unidade</a>
            </li>
          </ul>

            <div id="view-users">

            </div>
        </div>
      </div>
@endif

    </div>
    <div class="row">
      <div class="col-md-6">
        <div id="block-lesson" class="block">
          <div class="row">
            <div class="col-md-6 col-xs-6">
              <h4 class="">AULAS</h4>
            </div>
@if($unit_current->status == "E")
            <div class="col-md-6 col-xs-6">
              <a id="new-block" class="btn btn-default pull-right" href='{{"/lessons/new?unit=" . Crypt::encrypt($unit_current->id)}}'><i class="fa fa-plus"></i> Nova aula</a>
            </div>
@endif
          </div>
        </div>

{{ ""; $i = count($lessons) }}
@forelse( $lessons as $lesson )
        <div class="block data" key="{{Crypt::encrypt($lesson->id)}}">
          <div class="row">
            <div class="seq col-md-6 col-sm-6 col-xs-6">
              <p>Aula {{ $i-- }}</p>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-6">
  @if($unit_current->status == "E")
              <i class="pull-right fa fa-gears icon-default click" data-toggle="dropdown" aria-expanded="false"></i>
              <ul class="dropdown-menu dropdown-menu-right" role="menu">
                <li><a href='{{ URL::to("/lessons?l=" . Crypt::encrypt($lesson->id)) }}'><i class="fa fa-edit text-info"></i> Editar</a></li>
                <li><a href='{{ URL::to("/lessons/delete") }}' class="trash click"><i class="fa fa-trash text-danger"></i> Deletar</a></li>
              </ul>
  @endif
              <i class="pull-right fa fa-file-text-o icon-default infolesson click"></i>

            </div>
          </div>
          <div class="row">
            <div class="block-list-item">
              <div class="col-md-12">
                <p class="text-info"><i class="fa fa-calendar"></i> {{ date("d/m/Y", strtotime($lesson->date)) }}</p>
                <p  class="text-md text-blue">
                  <a href='{{ URL::to("/lessons?l=" . Crypt::encrypt($lesson->id)) }}'>{{ $lesson->title }}</a>
                </p>
                
                <p>{{ $lesson->description }}</p>
              </div>
            </div>
          </div>
        </div>
@empty

@endforelse

      </div>

      <div class="col-md-6">
        <div class="block">
          <div class="row">

            <div class="col-md-6 col-xs-6">
              <h4 class="">AVALIAÇÕES</h4>
            </div>
@if($unit_current->status == "E")
            <div class="col-md-6 col-xs-6">
              <a id="new-block" href='{{ URL::to("/avaliable/new?u=" . Crypt::encrypt($unit_current->id)) }}' class="btn btn-default pull-right"><i class="fa fa-plus"></i> Nova avaliação</a>
            </div>
@endif
          </div>
        </div>


@if($recovery)
        <div class="block">
          <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-6">
              <p>Avaliação {{count($exams)+1}}</p>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
  @if($unit_current->status == "E")
              <i class="pull-right fa fa-gears icon-default click" data-toggle="dropdown" aria-expanded="false"></i>
              <ul class="dropdown-menu dropdown-menu-right" role="menu">
                <li><a href='{{ URL::to("/avaliable/finalunit/" . Crypt::encrypt($unit_current->id)) . "?edit=e" }}'><i class="fa fa-edit"></i> Editar</a></li>
                
              </ul>
  @endif
              <i class="pull-right fa fa-search icon-default click"></i>
              <a href='{{ URL::to("/avaliable/finalunit/" . Crypt::encrypt($unit_current->id)) }}'><i class="pull-right fa fa-file-text-o icon-default click"></i></a>

            </div>
          </div>
          <div class="row">
            <div class="block-list-item">
              <div class="col-md-12">
                <p class="text-info"><i class="fa fa-calendar"></i> {{ $recovery->date }}</p>
                <p class="text-md text-info">
                  <a href='{{ URL::to("/avaliable/finalunit/" . Crypt::encrypt($unit_current->id)) }}'>
                  {{ $recovery->title }}
                  </a>
                </p>
                <p>{{ $recovery->comments }}</p>
              </div>
            </div>
          </div>
        </div>
@endif




{{ ""; $i = count($exams) }}
@forelse( $exams as $exam )
        <div class="block data" key="{{Crypt::encrypt($exam->id)}}">
          <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-6">
              <p>Avaliação {{ $i-- }}</p>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
  @if($unit_current->status == "E")
              <i class="pull-right fa fa-gears icon-default click" data-toggle="dropdown" aria-expanded="false"></i>
              <ul class="dropdown-menu dropdown-menu-right" role="menu">
                <li><a href='{{ URL::to("/avaliable?e=" . Crypt::encrypt($exam->id)) }}'><i class="fa fa-edit"></i> Editar</a></li>
                <li><a href="{{ URL::to("/avaliable/delete") }}" class="trash click"><i class="fa fa-trash text-danger"></i> Deletar</a></li>
              </ul>
  @endif
              <i class="pull-right fa fa-search icon-default click"></i>
              <a href='{{ URL::to("/avaliable/liststudentsexam/" . Crypt::encrypt($exam->id)) }}'><i class="pull-right fa fa-file-text-o icon-default click"></i></a>

            </div>
          </div>
          <div class="row">
            <div class="block-list-item">
              <div class="col-md-12">
                <p class="text-info"><i class="fa fa-calendar"></i> {{ date("d/m/Y", strtotime($exam->date)) }}</p>
                <p class="text-md text-info">
                  <a href='{{ URL::to("/avaliable/liststudentsexam/" . Crypt::encrypt($exam->id)) }}'>
                  {{ $exam->title }}
                  </a>
                </p>
                <p>{{ $exam->comments }}</p>
              </div>
            </div>
          </div>
        </div>
@empty

@endforelse
      </div>
    </div>

  </div>
</div>


@include('modules.infolessons')
@stop
