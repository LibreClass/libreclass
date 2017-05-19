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
{{ HTML::script('js/teacher.js') }}
@stop

@section('body')
@parent

<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">

    <div class="block">
      <div class="row">
        <div class="col-sm-10">
          <h3 class="text-blue"><b><i class="fa fa-user"></i> Informações</b></h3>
        </div>
        <div class="col-sm-2 text-right">
          <a href="{{ URL::to("/user/teacher")}}" class="btn btn-block btn-default">Voltar</a>
        </div>
      </div>
    </div>

    <div id="block" class="block">

      <div class="row">
        <div class="col-xs-12">
          <h4>{{ $profile->name }}</h4><br>
          <p><b>Matrícula: </b> {{ $profile->enrollment }}</p>
          <p><b>Email: </b> {{ $profile->email == "" ? "Email não cadastrado" : $profile->email }}</p>
          <p><b>Data de Nascimento: </b> {{ date("d/m/Y", strtotime($profile->birthdate)) }}</p>
          <p><b>Sexo: </b> {{ $profile->gender == "F" ? "Feminino" : ($profile->gender == "M" ? "Masculino" : "Sexo não informado") }}</p>
          <p><b>Formação Acadêmica: </b> {{ $profile->formation }}</p>
        </div>
      </div>
    </div>

    <div id="block" class="block">

      <div class="row">
        <div class="col-xs-12">
          <h4>Disciplinas que atua</h4>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <table class="table table-hover">
            <tbody>
              @foreach($profile->offers as $offer)
                @if($offer->classe->period->course->idInstitution == $user->id)
                <tr>
                  <td>
                    <span class="bold">{{ $offer->discipline->name }}</span><br>
                    <span class="text-sm text-muted">{{ $offer->classe->period->course->name }} / {{ $offer->classe->period->name }}</span>
                  </td>
                </tr>
                @endif
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

    </div>

    <div id="block" class="block">

      <div class="row">
        <div class="col-sm-10">
          <h4>Disciplinas que pode atuar</h4>
        </div>
        <div class="col-sm-2">
          <button class="add-teacher-discipline btn btn-default pull-right">Adicionar</button>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <table class="table table-hover">
            <tbody>
              @foreach($profile->disciplines as $discipline)
                @if($discipline->period->course->idInstitution == $user->id)
                  <tr>
                    <td>
                      <span class="bold">{{ $discipline->name }}</span><br>
                      <span class="text-sm text-muted">{{ $discipline->period->course->name }} / {{ $discipline->period->name }}</span>
                    </td>
                  </tr>
                @endif
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

    </div>


  </div>
</div>

@include("modules.addTeacherDiscipline", ["profile" => $profile])

@stop
