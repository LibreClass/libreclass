@extends('social.master')

@section('css')
@parent
{{ HTML::style('css/classes-group.css') }}
@stop

@section('js')
@parent
{{ HTML::script('js/classes-group.js') }}
{{ HTML::script('js/classes.js') }}
{{ HTML::script('js/validations/modulesClasses.js') }}
@stop

@section('body')
@parent

<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">

    <div id="block" data-class="{{ $class->id }}">

      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="text-blue"><i class="fa fa-random"></i> <b>Agrupar ofertas</b></h3>
            </div>
            {{-- <div class="col-sm-6 text-right">
              <button id="new-class" class="btn btn-primary btn-block-xs"><i class="fa fa-plus fa-fw"></i><b> Nova turma</b></button>
            </div> --}}
          </div>
        </div>
      </div>

      <div class="panel panel-default helper">
        <div class="panel-heading">
          <div class="panel-title text">Deseja agrupar as ofertas da turma <b>{{ $class->name }}</b>?</div>
        </div>
        <div class="panel-body">
          {{-- <p>Ao agrupar ofertas você permitirá que um professor possa realizar aulas e avaliações que sejam replicadas nas ofertas que foram agrupadas.</p> --}}
          <div class="list-group">
            <a href="#" class="list-group-item" data-toggle="modal" data-target=".m-1">
              <i class="fa fa-check text-success" aria-hidden="true"></i> Gerenciar múltiplas ofertas
            </a>
            <a href="#" class="list-group-item" data-toggle="modal" data-target=".m-2">
              <i class="fa fa-check text-success" aria-hidden="true"></i> Replicar automaticamente frequência de aulas
            </a>
            <a href="#" class="list-group-item" data-toggle="modal" data-target=".m-3">
              <i class="fa fa-check text-success" aria-hidden="true"></i> Replicar automaticamente resultados de avaliações
            </a>
          </div>
          <div class="buttons-hide">
            <a href="/classes" class="btn btn-default">Não</a>
            <button type="button" class="btn btn-primary">Sim, Selecionar ofertas</button>
          </div>
        </div>
      </div>

      <div class="modal fade m-1" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Gerenciar múltiplas ofertas</h4>
            </div>
            <div class="modal-body">
              <p>Há casos em que um professor tem mais de uma oferta lecionada consecutivamente.</p>
              <p>Exemplo: O professor irá lecionar a disciplina <b>Aritmética</b> e, logo em seguida, a disciplina <b>Geometria</b> para a mesma turma.</p>
              <p>A instituição poderá criar a oferta agrupada <b class='text-success'>Matemática</b> com as ofertas Aritmética e Geometria.</p>
              <p>Quando o professor criar uma nova aula em <b class='text-success'>Matemática</b>, na verdade ele estará criando uma aula para ambas as disciplinas. De forma semelhante, ao preencher uma frêquência na oferta agrupada a mesma será replicada para as disciplinas.</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade m-2" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Replicar automaticamente frequência de aulas</h4>
            </div>
            <div class="modal-body">
              <p>Quando uma aula for criada em uma disciplina agrupada a mesma será replicada para as outras disciplinas. Assim, o professor terá agilidade ao gerenciar disciplinas que são semelhantes.</p>
              <p>Exemplo: Se o professor irá lecionar a disciplina de <b>Aritmética</b> e depois <b>Geometria</b>, consecutivamente, e sabe que a frequência será igual nas duas aulas, basta criar a aula na disciplina agrupada <b class="text-success">Matemática</b> que a aula será criada sibultaneamente nas disciplinas citadas anteriormente.</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade m-3" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Replicar automaticamente resultados de avaliações</h4>
            </div>
            <div class="modal-body">
              <p>Uma mesma avaliação poderá ser criada em uma disciplina agrupada com objetivo de ser replicada nas demais disciplinas.</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
</div>

@stop
