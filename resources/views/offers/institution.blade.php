@section('css')
@parent
{{ HTML::style("css/bootstrap-toggle.min.css") }}
@stop

@section('js')
@parent
<script src="/js/bootstrap-toggle.min.js"></script>
<script src="/js/offer-toogle.js"></script>
@stop

@extends('modules.offers')



@section('offer-option')
@parent

<div class="block">
  <div class="row">
    <div class="col-md-10 col-sm-10">
      <ol class="breadcrumb">
        <li><b>{{ $classe->school_year }}</b></li>
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
        <div class="col-md-9 col-sm-9 col-xs-9">
					<p class="text-link text-md text-medium">
						<strong>{{ $offer->getDiscipline()->name }}</strong>
					</p>
				</div>
        <div class="col-md-3 col-sm-3 col-xs-3 text-right">
          @if ($offer->offer)
					  <span class="label label-primary">GRUPO: {{ $offer->offer->discipline->name }}
          @endif
          @if ($offer->grouping == 'M')
					  <span class=" text-right label label-default">Grupo de disciplinas</span>
          @endif
        </div>
      </div>

      <div class="row">
        <div class="block-list-item">
          <div class="col-md-12 offer">
            <div class="list-inline">
              <button class="add-teacher click btn btn-default btn-block-xs" key="{{ encrypt($offer->id) }}"><i class="fa fa-link"></i> Editar</button>
              <a href="{{ URL::to("/classes/offers/students/".encrypt($offer->id)) }}" class="btn btn-default btn-block-xs"><i class="fa fa-graduation-cap"></i> Gerir Alunos</a>
              <button class="view-syllabus click btn btn-default btn-block-xs" key="{{ encrypt($offer->getDiscipline()->id) }}"><i class="fa fa-file-text"></i> Ementa</button>
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
            @foreach ($offer->units as $unit)
              <li >
                <input
                  {{ !strcmp($unit->status, 'E')? "checked" : "" }}
                  class="toggle-btn toggle-event" type="checkbox" data-toggle="toggle"
                  data-on="Bloquear"
                  data-off="Desbloquear"
                  data-size="mini" unit="{{ encrypt($unit->id) }}">
                &nbsp;&nbsp;
                <a class="text-link" target="_blank" href='{{ URL::to("/classes/units/report-unit/".encrypt($unit->id)) }}'> Unidade {{ $unit->value }}</a>
              </li>
            @endforeach
            </ul>
            <div class="row">
            	<div class="col-xs-12">
								<div class="list-inline">
									<button class="btn btn-default btn-block-xs new-unit" url='{{ URL::to("/classes/offers/unit/".encrypt($offer->id)) }}'> + Nova Unidade</button>
                  @if (!$offer->units->isEmpty())
                    <button
                      class="btn btn-default btn-block-xs delete-unit"
                      url='{{ URL::to("/classes/offers/delete-last-unit/".encrypt($offer->id)) }}'
                    >
                      <i class="fa fa-trash fa-fw"></i>Deletar Unidade
                    </button>
                  @endif
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
