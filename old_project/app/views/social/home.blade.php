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
            <li> <span class='help-disciplines-teacher'>Como gerenciar meus diários?</span></li>
            <li> <span class='help-alter-calc'>Como definir o cálculo de notas?</span></li>
            <li> <span class='help-lesson-teacher'>Como criar uma aula?</span></li>
            <li> <span class='help-avaliable-teacher'>Criar criar uma avaliação?</span></li>
            <li> <span class='help-frequencies-teacher'>Como lançar a frequência dos alunos?</span></li>
            <li> <span class='help-exams-teacher'>Como lançar notas das avaliações?</span></li>
            <li> <span class='help-recovery-unit'>Como criar uma recuperação de uma unidade?</span></li>
            <li> <span class='help-recovery-final'>Como criar uma recuperação final de uma disciplina?</span></li>
            <li> <span class='help-infolesson-teacher'>Como ver o resumo de uma aula?</span></li>
            
            
            
          </ul>
       </li><br>
       @elseif( $user->type == "I" )
        <li><h4>Para instituições:</h4>
          <ul>
            <li> <span class='help-courses-inst'>Como criar um curso?</span></li>
            <li> <span class='help-periods-inst'>Como criar uma série?</span></li>
            <li> <span class='help-disciplines-inst'>Como criar uma disciplina?</span></li>
            <li> <span class='help-classes-inst'>Como criar uma turma?</span></li>
            <li> <span class='help-units-inst'>Como criar uma unidade?</span></li>
            <li> <span class='help-teachers-inst'>Como adicionar um professor?</span></li>
            <li> <span class='help-linking-inst'>Como vincular professor a uma disciplina?</span></li>
            <li> <span class='help-access-inst'>Como liberar o login para um professor?</span></li>
            <li> <span class='help-students-inst'>Como adicionar um aluno?</span></li>
          </ul>
        </li>
       @endif
      </ul>
      
    </div>
          
    </div>
</div>

@include('help.modal')

@stop