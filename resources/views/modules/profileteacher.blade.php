@extends('social.master')

@section('css')
@parent
<link media="all" type="text/css" rel="stylesheet" href="/css/blocks.css">
<link media="all" type="text/css" rel="stylesheet" href="/css/profileTeacher.css">
@stop

@section('js')
@parent
<script src="/js/blocks.js"></script>
<script src="/js/student.js"></script>
<script src="/js/validations/modulesAddStudents.js"></script>
<script src="/js/teacher.js"></script>
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

    <div class="block">
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

    <div class="block">
      <div class="row">
        <div class="col-xs-10">
          <h4>Disciplinas</h4>
        </div>
        <div class="col-xs-2">
          <button class="add-teacher-discipline btn btn-default pull-right">Adicionar</button>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
            @if(!$hasDiscipline) 
            <span>Não há disciplinas relacionadas a este professor</span>
            @else     
          <table class="table table-bordered disciplines">
            <thead>
              <tr>
                <th>Curso</th>
                <th>Período</th>
                <th>Disciplina</th>
              </tr>  
            </thead>
            <tbody> 
              @foreach($courses as $course)
                @foreach($course->periods as $period)
                  @foreach($period->disciplines as $discipline)
                    @if (\App\Bind::where("user_id", $profile->id)->where("discipline_id", $discipline->id)->first())
                      <tr>
                        <td>{{ $course->name }}</td>
                        <td>{{ $period->name }}</td>
                        <td>{{ $discipline->name }}</td>
                      </tr>
                    @endif
                  @endforeach
                @endforeach
              @endforeach
            </tbody>
          </table> 
          @endif  
        </div>
      </div>
    </div>
  </div>
</div>

@include("modules.addTeacherDiscipline", ["profile" => $profile])

@stop