@extends('social.master')

@section('css')
@parent
{{ HTML::style('css/blocks.css') }}
{{ HTML::style('css/help.css') }}
@stop

@section('js')
@parent
{{ HTML::script('js/blocks.js') }}
{{ HTML::script('js/course.js') }}
{{ HTML::script('js/help.js') }}
{{ HTML::script('js/validations/socialCourses.js') }}
@stop

@section('body')
@parent

<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">

    <div class="block">
      <div class="row">
        <div class="col-sm-12 col-xs-12">
          <h3 class="text-blue"> <i class="glyphicon glyphicon-paperclip"></i> <b> Bem vindo ao LibreClass! </b></h3>
        </div>
      </div>
    </div>
    
    <div class="block">
      <h3 class="text-blue"><b>Conheça o LibreClass</b></h3> <br>
      
      <img id="img-libreclass" class="center-block img-rounded" alt="Educação mais eficiente" src="images/libreclass.png">
      
      <p>Pensamos nos principais problemas enfrentados por professores, alunos e instituições de ensino, esta ferramenta 
        visa mudar o seu ponto de vista sobre sistemas online.</p>
      <p><i class="glyphicon glyphicon-info-sign color-blue"></i> Conheça as ferramentas que o LibreClass disponibiliza:</p>
      
      <ul class="help-list">        
       @if( $user->type == "P" )
        <li><h4>Para professores:</h4>
          <ul>
            <!--<li> <span class='help-courses'>Gerenciar cursos</span></li>-->
            <li> <span class='help-disciplines'>Gerenciar diários e disciplinas</span></li>
            <li> <span class='help-frequencies'>Controlar frequências de alunos</span></li>
            <li> <span class='help-exams'>Controlar notas de alunos</span></li>
            <li> <span class='help-notes'>Incluir notas de aula</span></li>
            <li> <span class='help-planning'>Criar planos de aula</span></li>
            <li> <span class='help-recovery'>Gerenciar recuperações para uma disciplina</span></li>
          </ul>
       </li><br>
       @elseif( $user->type == "I" )
        <li><h4>Para instituições:</h4>
          <ul>
            <li> <span class='help-'>Instituições possuem todas as ferramentas de um professor</span></li>
            <li> <span class='help-'>Criar cursos, turmas e unidades</span></li>
            <li> <span class='help-'>Criar disciplinas e ofertar disciplinas</span></li>
            <li> <span class='help-'>Vincular disciplinas a professores para que os mesmos possam ter acesso</span></li>
            <li> <span class='help-'>Adicionar e remover professores e alunos. </span></li>
          </ul>
        </li>
       @endif
      </ul>
      
    </div>
          
    </div>
</div>

@include('help.modal')

@stop