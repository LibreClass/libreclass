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
            <div class="f-container f-align-center">
                <div class="f-grow-3">
                    <h3 class="text-blue"><i class="fa fa-user"></i> <b>Informações do professor</b></h3>
                </div>
                <div class="f-grow-1 text-right">
                    <lc-button variant="secondary" id="backTeacher"> Voltar </lc-button>
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

            <div class="f-container f-align-center">
                <div class="f-grow-3">
                    <h4 class="text-blue">Disciplinas</h4>
                </div>
                <div class="f-grow-1 text-right">
                    <lc-button class="add-teacher-discipline"> Adicionar </lc-button>
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
                                <th>{{ ucfirst(strtolower(session('period.singular'))) }}</th>
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

<script type="application/javascript">
    $(function() {
        $('#backTeacher').click(function() {
            window.location.href = '/user/teacher';
        });
    });
</script>

@include("modules.addTeacherDiscipline", ["profile" => $profile])

@stop