@extends('modules.offers')


@section('offer-option')
@parent

<div id="block-title" class="block">
  <div class="row">
    <div class="col-md-12">
      <h3 class="text-blue"><i class="fa fa-book"></i> <b>Meus Diários</b></h3>
    </div>
  </div>
</div>

@forelse( $lectures as $lecture )
  <div class="row">
      <div class="col-md-12">
        <div class="block">
          <div class="row">
            <div class="col-md-10 col-sm-10 col-xs-10">
              <p class="text-muted"> {{ $lecture->getOffer()->getDiscipline()->getPeriod()->getCourse()->getInstitution()->name }} &nbsp; / &nbsp;
                  {{ $lecture->getOffer()->getDiscipline()->getPeriod()->getCourse()->name }} &nbsp; / &nbsp;
                  {{ $lecture->getOffer()->getDiscipline()->getPeriod()->name }} &nbsp; / &nbsp;
                  {{ $lecture->getOffer()->getClass()->name }} {{ $lecture->getOffer()->getClass()->class }}</p>
            </div>

          </div>
          <div class="row">
            <div class="block-list-item">
              <div class="col-md-12">
                <a href='{{ URL::to("/lectures/units?u=" . Crypt::encrypt($lecture->getOffer()->getLastUnit()->id) ) }}' class="text-md text-link">{{ $lecture->getOffer()->getDiscipline()->name }}</a>
                <p>Sala: {{$lecture->getOffer()->classroom}}</p>
                <br>
              </div>

              <div class="col-md-6">
                <div class="list-group">
                  <a class="list-group-item text-link view-syllabus click" key="{{Crypt::encrypt($lecture->getOffer()->getDiscipline()->id)}}"><i class="fa fa-file-text"></i> Ementa</a>
                  <a href='{{ URL::to("/lectures/frequency/" . Crypt::encrypt($lecture->getOffer()->id)) }}' class="list-group-item text-link">
                    <i class=" fa fa-check-square-o"></i> Frequência
                  </a>
                  <a href='{{ URL::to("lectures/finalreport/".Crypt::encrypt($lecture->getOffer()->id)) }}' class="list-group-item text-link">
                    <i class="fa fa-bar-chart-o"></i> Resultados
                  </a>
                </div>
              </div>

              <div class="col-md-6">
                <div class="list-group">
                  @foreach( $lecture->getOffer()->getUnits() as $unit )
                    @if($unit->status == "D")
                    <a class="list-group-item" href='{{ URL::to("/lectures/units?u=" . Crypt::encrypt($unit->id)) }}'>
                      Unidade {{ $unit->value }}
                      <label class="label label-danger"><i class="fa fa-close"></i> Desativada</label>
                    </a>
                    @else
                      <a class="list-group-item" href='{{ URL::to("/lectures/units?u=" . Crypt::encrypt($unit->id)) }}'> Unidade {{ $unit->value }} </a>
                    @endif
                  @endforeach
                  <a class="list-group-item" href="{{"/avaliable/finaldiscipline/" . Crypt::encrypt($lecture->idOffer)}}"> Prova Final </a>
                </div>
              </div>



              <div class="col-md-12">
                <div class="visible-none syllabus">
                    <p>{{ $lecture->getOffer()->getDiscipline()->ementa }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

@empty

  <div class="block">
    <div class="text-center text-md">Você não possui cursos diários.</div>
  </div>

  <div class="block">
    <div class="">
      <h3>Como posso obter diários?</h3>
      <br>
      <p>Para obter diários é necessário que uma instituição de ensino vincule a sua conta a uma disciplina. 
         Quando isto acontecer, a disciplina liberada pela instituição irá aparecer aqui e você poderá ter acesso 
         à mesma.</p>
    </div>
  </div>

@endforelse

@include("offers.ementa")

@stop
