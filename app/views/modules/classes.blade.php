@extends('social.master')

@section('css')
@parent
{{ HTML::style('css/blocks.css') }}
@stop

@section('js')
@parent
{{ HTML::script('js/blocks.js') }}
{{ HTML::script('js/classes.js') }}
{{ HTML::script('js/validations/modulesClasses.js') }}
@stop

@section('body')
@parent

<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">

    <div id="block">
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="text-blue"><i class="icon-classes"></i> <b>Minhas Turmas</b></h3>
            </div>
            <div class="col-sm-6 text-right">
              <button id="new-class" class="btn btn-primary btn-block-xs"><i class="fa fa-plus fa-fw"></i><b> Nova turma</b></button>
            </div>
          </div>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-12">
              <div class="dropdown pull-right">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
                  <i class="fa fa-cog fa-fw"></i> Administrar
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                  <li id="create-unit" role="presentation"><a role="menuitem" tabindex="-1" href="#"><i class="fa fa-plus fa-fw text-primary"></i> Criar Nova Unidade</a></li>
<!--									<li id="delete-unit" role="presentation"><a role="menuitem" tabindex="-1" href="#"><i class="fa fa-trash fa-fw text-danger"></i> Excluir Unidade</a></li>-->
                  <li id="block-unit"><a role="menuitem" tabindex="-1" href="#"><i class="fa fa-lock fa-fw text-danger"></i> Bloquear Unidade</a></li>
									<li id="unblock-unit"><a role="menuitem" tabindex="-1" href="#"><i class="fa fa-unlock fa-fw text-muted"></i> Desbloquear Unidade</a></li>
                  <!--<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Encerrar período letivo</a></li>-->
                </ul>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
            @if( $classes == null )
            <div class="text-md text-center">Não há turmas cadastradas</div>
            @else
              <table class="table table-hover table-striped">
                <thead>
                  <tr>
                    <th style="width: 40%"><span class="text-md">Turma</span></th>
                    <th><span class="text-md">Série</span></th>
                    <th><span class="text-md">Curso</span></th>

                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach( $classes as $classe )
                    @if( $classe->status == 'E' )
                    <tr class="data" key="{{Crypt::encrypt($classe->id)}}">
                      <td>
                        <a class="btn btn-default" href='{{ URL::to("classes/offers?t=".Crypt::encrypt($classe->id)) }}'>
                          <?= isset(str_split($classe->classe, 30)[1]) ? str_split($classe->classe, 30)[0]."..." : "$classe->classe" ?>
                        </a>
                      </td>
                      <td>{{ $classe->period }}</td>
                      <td>{{ $classe->name }}</td>

                      <td>
                        <div class="col-md-12">
                          <i class="pull-right fa fa-gears icon-default click" data-toggle="dropdown" aria-expanded="false"></i>
                          <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            <li><a class="classe-edit"><i class="fa fa-edit text-primary"></i> Editar</a></li>
                            <li><a class="change-status click" href="/classes/change-status" value="B"><i class="fa fa-times-circle" style="color: red;"></i> Bloquear</a></li>
                            <li><a class="trash click"><i class="fa fa-trash text-danger"></i> Deletar</a></li>
                          </ul>
                        </div>
                      </td>
                    </tr>
                    @endif
                  @endforeach

                  @foreach( $classes as $classe )
                    @if( $classe->status == 'B' )
                    <tr class="data danger" key="{{Crypt::encrypt($classe->id)}}">
                      <td><a class="btn btn-default" href='{{ URL::to("classes/offers?t=".Crypt::encrypt($classe->id)) }}'>{{ $classe->classe }}</a></td>
                      <td>{{ $classe->name }}</td>
                      <td>{{ $classe->period }}</td>
                      <td>
                        <div class="col-md-12">
                          <i class="pull-right fa fa-gears icon-default click" data-toggle="dropdown" aria-expanded="false"></i>
                          <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            <li><a class="classe-edit"><i class="fa fa-edit text-primary"></i> Editar</a></li>
                            <li><a class="change-status click" href="/classes/change-status" value="E"><i class="fa fa-times-circle" style="color: blue;"></i> Ativar</a></li>
                            <li><a class="trash click"><i class="fa fa-trash text-danger"></i> Deletar</a></li>
                          </ul>
                        </div>
                      </td>
                    </tr>
                    @endif
                  @endforeach

                </tbody>
              </table>
            @endif
            </div>
          </div>
        </div>
      </div>
    </div>


  </div>
</div>

@include("modules.addClassesModal")
@include("offers.blockUnitModal")
@include("offers.unblockUnitModal")
@include("offers.createUnitModal")
<!--@include("offers.deleteUnitModal")-->

@stop
