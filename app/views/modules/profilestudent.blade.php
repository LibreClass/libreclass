@extends('social.master')

@section('css')
@parent
{{ HTML::style('css/blocks.css') }}
@stop

@section('js')
@parent
{{ HTML::script('js/blocks.js') }}
{{ HTML::script('js/student.js') }}
{{ HTML::script('js/validations/modulesAddStudents.js') }}
@stop

@section('body')
@parent

<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">

    <div id="block" class="panel panel-default panel-daily">
      <div class="panel-heading">
        <h3>{{ $profile->name }}</h3>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-xs-12">
            <p><b>Matrícula: </b> {{ $profile->enrollment }}</p>
            <p><b>Email: </b> {{ $profile->email == "" ? "Email não cadastrado" : $profile->email }}</p>
            <p><b>Data de Nascimento: </b> {{ date("d/m/Y", strtotime($profile->birthdate)) }}</p>
            <p><b>Sexo: </b> {{ $profile->gender == "F" ? "Feminino" : ($profile->gender == "M" ? "Masculino" : "Sexo não informado") }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-body">
        <!-- <div class="btn-group">
          <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Ações
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" role="menu">
            <li><a href="#">Atestados</a></li>
            <li><a href="#" class="student-history">Histórico</a></li>
          </ul>
        </div> -->
        <button class="btn btn-default" id="btnLinkStudent" data="{{ Crypt::encrypt($profile->id) }}"><i class="icon-classes fa-fw"></i> Vincular turma</button>
        <button class="btn btn-default" id="btnCertificate" data="{{ Crypt::encrypt($profile->id) }}"><i class="fa fa-file-o fa-fw"></i> Atestado</button>
        <button class="btn btn-default" id="btnReport" data="{{ Crypt::encrypt($profile->id) }}"><i class="fa fa-file-o fa-fw"></i> Boletim</button>
        @if($profile->type == "N")
          <button class="btn btn-default" id="btnInvite" data="{{ Crypt::encrypt($profile->id) }}"><i class="fa fa-envelope-o fa-fw"></i> Enviar convite</button>
        @endif
        <hr>

        @include("institution.reportStudent", ["listclasses" => $listclasses])
      </div>
    </div>

    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
      <div class="panel panel-default">
        <div class="panel-heading click" role="tab" id="headingOne" class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
          <h4><i class="fa fa-file-o fa-fw"></i><b> Atestados</b></h4>
        </div>

        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
          <div class="panel-body">
            <table class="table">
              <thead>
                <tr>
                  <td>Início do Atestado</td>
                  <td>Validade</td>
                  <td>Descrição</td>
                </tr>
              </thead>
              <tbody>
                @foreach($attests as $attest)
                <tr>
                  <td>{{ date("d/m/Y", strtotime($attest->date)) }}</td>
                  <td>{{ $attest->days ." dias"}}</td>
                  <td>{{ $attest->description }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>

  </div>
</div>

@include("modules.student.linkingStudentClasse", ["listidsclasses" => $listidsclasses])
@include("modules.student.modalCertificate")
@include("modules.student.modalInvite")
@include("modules.student.modalScholarReport", ["listCourses" => $listCourses])

@stop
