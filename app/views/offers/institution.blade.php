@extends('modules.offers')


@section('offer-option')
@parent

<div class="block">
  <div class="row">
    <div class="col-md-10 col-sm-10">
      <ol class="breadcrumb text-md text-sm">
        <li><b>{{ $course->name }}</b></li>
        <li><b>{{ $period->name }}</b></li>
        <li class="active"><b>{{ $classe->class }}</b></li>
      </ol>
    </div>
    <div class="col-md-2 col-sm-2text-right">
      <a class="btn btn-block btn-default btn-block-xs" href="{{ URL::to("classes") }}">Voltar</a>
    </div>
  </div>
  <br>
  <div class="row">
    <div class="col-md-12">
      <ul class="list-inline">
        <li><button class="btn btn-default">Gerenciar Alunos</button></li>
      </ul>
    </div>
  </div>
</div>


@foreach ( $offers as $offer )

<div class="row">
  <div class="col-md-12">
    <div class="block">
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <p class="text-md text-link">{{ $offer->getDiscipline()->name }}</p>
        </div>
<!--        <div class="col-md-1 col-sm-1 col-xs-1">
          <i class="pull-right fa fa-gears icon-default click" data-toggle="dropdown" aria-expanded="false"></i>
          <ul class="dropdown-menu dropdown-menu-right" role="menu">
            <li><a href=""><i class="fa fa-edit"></i> Editar</a></li>
            <li><a href=""><i class="fa fa-trash"></i> Deletar</a></li>
          </ul>
        </div>-->
      </div>

      <div class="row">
        <div class="block-list-item">
          <div class="col-md-12">

            <p>{{ ($lecture = $offer->getLectures()) ? "Professor: " . $lecture->getUser()->name : "Sem professor vinculado" }}</p>
            <p>Sala de Aula: {{ $offer->classroom != null ? $offer->classroom : "Não informada"}}</p>
            <ul class="list-unstyled">
            @foreach( $offer->getUnits() as $unit )
              <li class="">
                <input
                  {{ !strcmp($unit->status, 'E')? "checked" : "" }}
                  class="toggle-btn toggle-event" type="checkbox" data-toggle="toggle"
                  data-on="<i class='glyphicon glyphicon-thumbs-up'></i>&nbsp;&nbsp;Ativa"
                  data-off="<i class='glyphicon glyphicon-thumbs-down'></i>&nbsp;&nbsp;Inativa"
                  data-size="mini" unit="{{ Crypt::encrypt($unit->id) }}">
                &nbsp;&nbsp;
                <a class="text-link" href='{{-- URL::to("panel?unit=".Crypt::encrypt($unit->id)) --}}#'> Unidade {{ $unit->value }}</a>
              </li>

            @endforeach
              <li>
                <a class="text-link" href='{{ URL::to("/classes/offers/unit/".Crypt::encrypt($offer->id)) }}'> + Nova Unidade</a>
              </li>
            </ul>
            <p class="add-teacher text-link click"><i class="fa fa-link"></i> Vincular professor</p>
            
            <div class="modal fade visible-none modalTeacherOffer" tabindex="-1" role="Modal Add Teacher Offer" aria-labelledby="modalTeacherOffer" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  {{ Form::open(["url" => URL::to("/classes/offers/teacher")]) }}
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title text-blue"><b><i class="fa fa-link"></i> Vincular Professor</b></h3>
                  </div>
                  <div class="modal-body">
                    
                    <div class="form-group">
                      {{ Form::hidden("offer", Crypt::encrypt($offer->id) ) }}
                      {{ Form::hidden("prev", URL::full() ) }}
                      {{ Form::label("teacher", "Professor", ["class" => "control-label"] ) }}
                      <span class="help-block text-muted">Selecione o professor que irá lecionar a disciplina.</span>
                      {{ Form::select("teacher", $teachers, null, ["class" => "form-control input-lg"] ) }}
                    </div>
                    <div class="form-group">
                      {{ Form::label("classroom", "Sala de Aula",  ["class" => "control-"])}}
                      <span class="help-block text-muted">Informe o nome da sala de aula onde será lecionada a disciplina.</span>
                      {{ Form::text("classroom", $offer->classroom,  ["class" => "form-control input-lg"])}}
                    </div>
                    
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">Cancelar</button>
                    <button class="pull-right btn btn-lg btn-primary">Vincular</button>
                  </div>
                  {{ Form::close() }}
                </div>
              </div>
            </div>
            
            <div class="visible-none add-teacher-form">
              
            </div>
            <p class="text-link view-syllabus click"><i class="fa fa-file-text"></i> Ementa</p>
            <div class="visible-none syllabus">
              <p>{{ $offer->getDiscipline()->ementa }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


@endforeach



@stop
