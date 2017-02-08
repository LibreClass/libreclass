@section('css')
@parent
{{ HTML::style("css/bootstrap-toggle.min.css") }}
@stop

@section('js')
@parent
{{ HTML::script("js/bootstrap-toggle.min.js") }}
{{ HTML::script("js/offer-toogle.js") }}
@stop

@extends('modules.offers')



@section('offer-option')
@parent

<div class="block">
  <div class="row">
    <div class="col-md-10 col-sm-10">
      <ol class="breadcrumb text-md text-sm">
        <li><b>{{ $course->name }}</b></li>
        <li><b>{{ $period->name }}</b></li>
        <li class="active"><b>{{ $classe->fullName() }}</b></li>
      </ol>
    </div>
    <div class="col-md-2 col-sm-2 text-right">
      <a class="btn btn-block btn-default btn-block-xs" href="{{ URL::to("classes") }}">Voltar</a>
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
      </div>

      <div class="row">
        <div class="block-list-item">
          <div class="col-md-12 offer">
            <div class="list-inline">
              <button class="add-teacher click btn btn-default btn-block-xs" key="{{ Crypt::encrypt($offer->id) }}"><i class="fa fa-link"></i> Editar</button>
              <a href="{{ URL::to("/classes/offers/students/".Crypt::encrypt($offer->id)) }}" class="btn btn-default btn-block-xs"><i class="fa fa-graduation-cap"></i> Gerir Alunos</a>
              <button class="view-syllabus click btn btn-default btn-block-xs" key="{{ Crypt::encrypt($offer->getDiscipline()->id) }}"><i class="fa fa-file-text"></i> Ementa</button>
            </div>
            <br/>
            @if(!count($lectures = $offer->getAllLectures()))
              <p class='text-danger'><b>Sem professor vinculado</b></p>
            @else
              <ul class="list-inline insert-sheriff">
                @foreach($lectures as $lecture)
                  <li class="label label-select-multiple">
                    <div class="label-image">
                      <img src='{{ $lecture->getUser()->photo }}' class="img-responsive img-circle">
                    </div>
                    <span class="label-name">{{ $lecture->getUser()->name }}</span>
                  </li>
                @endforeach
              </ul>
            @endif
            <p>Sala de Aula: {{ $offer->classroom != null ? $offer->classroom : "Não informada"}}</p>
            @if($offer->day_period == "M")
              <p>Turno: Matutino</p>
            @elseif($offer->day_period == "V")
              <p>Turno: Vespertino</p>
            @elseif($offer->day_period == "N")
              <p>Turno: Noturno</p>
            @else  
              <p>Turno: Não informado</p>
            @endif
            <p>Quantidade máxima de aulas: {{ $offer->maxlessons }}</p>
            <i class="info" teacher='{{ json_encode($offer->teachers) }}'
                            classroom="{{ $offer->classroom }}"
                            maxlessons="{{ $offer->maxlessons }}"
                            day_period="{{ $offer->day_period }}"></i>


            <ul class="list-unstyled">
            @foreach( $offer->getUnits() as $unit )
              <li class="">
                <input
                  {{ !strcmp($unit->status, 'E')? "checked" : "" }}
                  class="toggle-btn toggle-event" type="checkbox" data-toggle="toggle"
                  data-on="Bloquear"
                  data-off="Desbloquear"
                  data-size="mini" unit="{{ Crypt::encrypt($unit->id) }}">
                &nbsp;&nbsp;
                <a class="text-link" target="_blank" href='{{ URL::to("/classes/units/report-unit/".Crypt::encrypt($unit->id)) }}'> Unidade {{ $unit->value }}</a>
              </li>

            @endforeach
            </ul>
            <div class="row">
            	<div class="col-xs-12">
								<div class="list-inline">
									<button class="btn btn-default btn-block-xs new-unit" url='{{ URL::to("/classes/offers/unit/".Crypt::encrypt($offer->id)) }}'> + Nova Unidade</button>
									<button class="btn btn-default btn-block-xs delete-unit" url='{{ URL::to("/classes/offers/delete-last-unit/".Crypt::encrypt($offer->id)) }}'><i class="fa fa-trash fa-fw"></i>Deletar Unidade</button>
								</div>
            	</div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>


@endforeach
@include("offers.linking-teacher")
@include("offers.ementa")

@stop
