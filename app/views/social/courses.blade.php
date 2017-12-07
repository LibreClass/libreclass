@extends('social.master')

@section('css')
@parent
{{ HTML::style('css/blocks.css') }}
@stop

@section('js')
@parent
{{ HTML::script('js/blocks.js') }}
{{ HTML::script('js/course.js') }}
{{ HTML::script('js/validations/socialCourses.js') }}
@stop

@section('body')
@parent
<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">
    <div id="block" class="block">
    @if(Session::has("message"))
      <div class="row">
        <div class="col-md-12 col-xs-12 col-sm-12">
          <div class="alert alert-info">{{ Session::get("message") }}</div>
        </div>
      </div>
    @endif
      <div class="row">
        <div class="col-md-6 col-xs-12">
          <h3 class="text-blue"><i class="fa fa-folder-o"></i> <b>Meus Cursos</b></h3>
        </div>
        @if( $user->type == "I" )
          <div class="col-md-6 col-xs-12">
            <div class="list-inline text-right">
              <button id="new-course" class="btn btn-primary"><b><i class="fa fa-plus"></i> Novo Curso</b></button>
              @if(!count($courses) == 0)
                <button id="new-periods" class="btn btn-primary"><b><i class="fa fa-plus"></i> Nova Série</b></button>
              @endif
            </div>

          </div>
        @endif
      </div>
    </div>

    <div class="block">
      <!--inicio da listagem de cursos -->
      @forelse( $courses as $course )
      <div class="panel panel-default data" key="{{ Crypt::encrypt($course->id) }}" >
        <div class="panel-heading">
          <div class="row">
            <div class="col-md-10 col-xs-10">
              <span><b><a class="course-edit click" key="{{ Crypt::encrypt($course->id) }}">{{ $course->modality ." ".$course->name }}</a></b></span>
            </div>
            <div class="col-md-2 col-xs-2 text-right">
              <i class="fa fa-gears icon-default click" data-toggle="dropdown" aria-expanded="false"></i>
              <ul class="dropdown-menu" role="menu">
                <li><a class="course-edit click" key="{{ Crypt::encrypt($course->id) }}"><i class="fa fa-edit text-info"></i> Editar</a></li>
                <li><a class="trash click"><i class="fa fa-trash text-danger"></i> Deletar</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="panel-body">
<!--          <span class="text-info"><i class="fa fa-calendar"></i> {{ date  ("d/m/Y", strtotime($course->created_at)) }}</span>-->
          <div class="row">
            <div class="col-md-6">

                <ul class="list-unstyled">
                  <li><b>Tipo de Ensino: </b>{{ $course->type }}</li>
                  <li><b>Modalidade: </b>{{ $course->modality }}</li>
                  <li><b>Trimestres/Unidades: </b>{{ $course->quant_unit }}</li>
                  <li><b>Faltas (Reprovação): </b>{{ $course->absentPercent . "%" }}</li>
                  <li><b>Média do Curso: </b>{{ $course->average }}</li>
                  <li><b>Média da Final: </b>{{ $course->averageFinal }}</li>
                  <li><b>Perfil Curricular: </b>
                    @if($course->curricularProfile != "")
                      <a href="{{"/uploads/curricularProfile/".$course->curricularProfile }}" target="_blank">Abrir arquivo</a>
                    @else
                    <span>Perfil não anexado.</span>
                    @endif
                  </li>
                </ul>
            </div>
            <div class="col-md-6">
              <h5><b>Séries</b></h5>
              <ul class="list-inline">
              @foreach($course->periods as $period)
                <li><span class="label label-default">{{ $period->name }}</span></li>
              @endforeach
              </ul>
            </div>
          </div>

        </div>
      </div>

      @empty
    </div> <!--Fim do bloco visível-->

    <div class="block">
      <div class="text-center text-md">Você não possui cursos cadastrados.</div>
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

      @endforelse <!--Fim da listagem de cursos -->
    </div> <!--Fim do bloco visível-->


  </div>
</div>

  @include("social.course-new")
  @include("social.periods-new", ["listcourses" => $listcourses])

@stop
