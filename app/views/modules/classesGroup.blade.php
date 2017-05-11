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

    <div id="block" data-classe="{{ $classe->id }}">

      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="text-blue"><i class="fa fa-share-alt"></i> <b>Agrupar ofertas</b></h3>
            </div>
            {{-- <div class="col-sm-6 text-right">
              <button id="new-class" class="btn btn-primary btn-block-xs"><i class="fa fa-plus fa-fw"></i><b> Nova turma</b></button>
            </div> --}}
          </div>
        </div>
      </div>

      <div class="panel panel-default helper">
        <div class="panel-heading">
          <div class="panel-title text">Deseja agrupar as ofertas da turma <b>{{ $classe->name }}</b>?</div>
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
            <button type="button" class="btn btn-primary step-1">Sim, Selecionar ofertas</button>
          </div>
        </div>
      </div>

      <div class="panel panel-default" hidden>
        <form>
          <div class="panel-heading">
            <div class="panel-title text">Selecione as ofertas que deseja agrupar da turma <b>{{ $classe->name }}</b></div>
          </div>
          <div class="panel-body">
            <div class="content">
              <div class="row">
                <div class="col-xs-12">
                  <div class="form-group">
                    <label for="class-group-name">Insira o nome da oferta que irá agrupar as demais</label>
                    <input name="name" type="text" class="form-control" id="class-group-name" placeholder="Nome da oferta">
                    <span class="help-block">Exemplo: Agrupar as ofertas <i>Aritmética</i> e <i>Geometria</i> com uma oferta com o nome <i>Matemática</i>.</span>
                  </div>
                </div>
              </div>
              <div>
                <label>Selecione as disciplinas que deseja agrupar</label>
              </div>
              <div class="row center pd-bottom form-group">
                @foreach ($classe->disciplines as $discipline)
                  <div class="col-lg-4 col-md-6 col-xs-12">
                    <div class="checkbox">
                      <label>
                        <input name="offers" type="checkbox" value="{{ $discipline->id }}">
                        {{ $discipline->name }}
                      </label>
                    </div>
                  </div>
                @endforeach
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <div class="buttons-hide">
                    <a href="/classes" class="btn btn-default">Cancelar</a>
                    <button type="button" class="btn btn-primary step-2">Agrupar ofertas</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
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
              <p>Exemplo: O professor irá lecionar a oferta <b>Aritmética</b> e, logo em seguida, a oferta <b>Geometria</b> para a mesma turma.</p>
              <p>A instituição poderá criar a oferta agrupada <b class='text-success'>Matemática</b> com as ofertas Aritmética e Geometria.</p>
              <p>Quando o professor criar uma nova aula em <b class='text-success'>Matemática</b>, na verdade ele estará criando uma aula para ambas as ofertas. De forma semelhante, ao preencher uma frêquência na oferta agrupada a mesma será replicada para as ofertas.</p>
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
              <p>Quando uma aula for criada em uma oferta agrupada a mesma será replicada para as outras ofertas. Assim, o professor terá agilidade ao gerenciar ofertas que são semelhantes.</p>
              <p>Exemplo: Se o professor irá lecionar a oferta de <b>Aritmética</b> e depois <b>Geometria</b>, consecutivamente, e sabe que a frequência será igual nas duas aulas, basta criar a aula na oferta agrupada <b class="text-success">Matemática</b> que a aula será criada sibultaneamente nas ofertas citadas anteriormente.</p>
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
              <p>Uma mesma avaliação poderá ser criada em uma oferta agrupada com objetivo de ser replicada nas demais ofertas.</p>
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
