@extends('modules.offers')

@section('js')
@parent
<script src="/js/lib/jquery-ui-sortable.min.js"></script>
<script src="/js/offer-sortable.js"></script>
@stop

@section('offer-option')
@parent

<div class="panel panel-default">
  <div id="block-title" class="panel-body">
    <div class="row">
      <div class="col-md-12">
        <h3 class="text-blue"><i class="fa fa-book"></i> <b>Meus Diários</b></h3>
      </div>
    </div>
  </div>
</div>
{{-- Início listagem de ofertas --}}
<div class="row">
  <div class="col-md-12">
    <ul class="list-unstyled" id="listOffers">

      @forelse ($lectures as $lecture)

        <li class="panel panel-default panel-daily" data-id="{{ encrypt($lecture->offer_id) }}">
          <div class="panel-heading sortLocal">
            <span class="text-muted cursor-move">
              {{ $lecture->offer->classe->period->course->institution->name }} &nbsp; / &nbsp;
              {{ $lecture->offer->classe->period->course->name }} &nbsp; / &nbsp;
              {{ $lecture->offer->classe->period->name }} &nbsp; / &nbsp;
              {{ $lecture->offer->getClass()->fullName() }}
            </span>
          </div>

          <div class="panel-body">
            <div class="row">
              <div class="col-md-12">
                {{--<a href='{{ URL::to("/lectures/units?u=" . encrypt($lecture->offer->units[count($lecture->offer->units)-1]->id) ) }}' class="text-md text-link">{{ $lecture->offer->discipline->name }}</a>--}}
								<h4 class="bold">
                  {{ $lecture->offer->discipline->name }}
                  @if($lecture->offer->grouping == 'M')
                    <small class="text-info">(Grupo de disciplinas)</small>
                  @endif
                  @if($lecture->offer->grouping == 'S')
                    <small class="text-muted">
                      {{-- @php
                        dd($lecture->offer->master);
                      @endphp --}}
                      ({{ $lecture->offer->master->discipline->name }})
                    </small>
                  @endif
                </h4>
                <p>Sala: {{$lecture->offer->classroom}}</p>
                <br>
              </div>

              <div class="col-md-6">
                <div class="list-group">
									@if($lecture->offer->grouping != 'M')
										<a class="list-group-item text-link view-syllabus click" key="{{encrypt($lecture->offer->discipline->id)}}"><i class="fa fa-file-text"></i> Ementa</a>
									@endif
                  <a href='{{ URL::to("/lectures/frequency/" . encrypt($lecture->offer->id)) }}' class="list-group-item text-link">
                    <i class=" fa fa-check-square-o"></i> Frequência
                  </a>
                  <a href='{{ URL::to("lectures/finalreport/".encrypt($lecture->offer->id)) }}' class="list-group-item text-link">
                    <i class="fa fa-bar-chart-o"></i> Resultados
                  </a>
                </div>
              </div>

              <div class="col-md-6">
                <div class="list-group">
                  @foreach( $lecture->offer->units as $unit )
                    @if($unit->status == "D")
                    <a class="list-group-item" target="_blank" href='{{ URL::to("/lectures/units/report-unit/".encrypt($unit->id)) }}'>
                      Unidade {{ $unit->value }}
                      <label class="label label-danger"><i class="fa fa-close fa-fw"></i>Desativada</label>
                    </a>
                    @else
                      <a class="list-group-item" href='{{ URL::to("/lectures/units?u=" . encrypt($unit->id)) }}'> Unidade {{ $unit->value }} </a>
                    @endif
                  @endforeach
                  <a class="list-group-item" href="{{"/avaliable/finaldiscipline/" . encrypt($lecture->offer_id)}}"> Prova Final </a>
                </div>
              </div>



              <div class="col-md-12">
                <div class="visible-none syllabus">
                    <p>{{ $lecture->offer->discipline->ementa }}</p>
                </div>
              </div>
            </div>
          </div>

        </li>

      @empty

        <div class="block">
          <div class="text-center">Você não possui diários de disciplinas</div>
        </div>

        <div class="block">
          <div >
            <h3>Como posso obter diários?</h3>
            <br>
            <p>Para obter diários é necessário que uma instituição de ensino vincule a sua conta a uma disciplina.
               Quando isto acontecer, a disciplina liberada pela instituição irá aparecer aqui e você poderá ter acesso
               à mesma.</p>
          </div>
        </div>

      @endforelse

    </ul>
  </div>
</div>

@include("offers.ementa")

@stop
