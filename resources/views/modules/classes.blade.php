@extends('social.master')

@section('css')
@parent
<link media="all" type="text/css" rel="stylesheet" href="/css/blocks.css">
@stop

@section('js')
@parent
<script src="/js/blocks.js"></script>
<script src="/js/classes.js"></script>
<script src="/js/validations/modulesClasses.js"></script>
@stop

@section('body')
@parent

<div class="row" id="view-classes">
    <div class="col-md-8 col-xs-12 col-sm-12">

        <div id="block">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-9">
                            <h3 class="text-blue"><i class="icon-classes"></i> <b>Turmas</b></h3>
                        </div>
                        <div class="col-xs-3 text-right">
                            <lc-button id="new-class"> Adicionar </lc-button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="input-group form-group">
                                <span class="input-group-addon">Ano letivo</span>
                                {{ Form::selectRange("school_year", 2017, date('Y'), $school_year, ["class" => "form-control"]) }}
                            </div>
                        </div>
                        <div class="col-xs-8">
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
                                    <li id="receive-classes"><a role="menuitem" tabindex="-1" href="#" title="Receber turmas do ano anterior"><i class="fa fa-share fa-fw text-muted"></i> Receber turmas</a></li>
                                    <!--<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Encerrar período letivo</a></li>-->
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @if( $classes == null )
                            <div class="text-center">Não há turmas cadastradas</div>
                            @else
                            <table class="table table-hover table-striped table-vertical-align">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">Turma</th>
                                        <th>Ano</th>
                                        <th>Curso</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach( $atual_classes as $classe )
                                    @if( $classe->status == 'E' )
                                    <tr class="classe-item data" id="{{encrypt($classe->id)}}">
                                        <td>
                                            <a class="btn btn-default" href='{{ URL::to("classes/offers?t=".encrypt($classe->id)) }}'>
                                                <?= isset(str_split($classe->classe, 30)[1]) ? str_split($classe->classe, 30)[0]."..." : "$classe->classe_name $classe->classe" ?>
                                            </a>
                                        </td>
                                        <td>{{ $classe->period }}</td>
                                        <td>{{ $classe->name }}</td>

                                        <td>
                                            <div class="col-md-12">
                                                <i class="pull-right fa fa-gears icon-default click" data-toggle="dropdown" aria-expanded="false"></i>
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                    <li><a class="classe-edit"><i class="fa fa-edit text-primary"></i> Editar</a></li>
                                                    <li><a class="group"><i class="fa fa-share-alt text-primary"></i> Agrupar ofertas</a></li>
                                                    <li><a class="progression"><i class="fa fa-level-up text-primary"></i> Importar alunos</a></li>
                                                    <li><a class="change-status click" href="/classes/change-status" value="B"><i class="fa fa-lock" style="color: red;"></i> Bloquear</a></li>
                                                    <li><a class="change-status click" href="/classes/change-status" value="F"><i class="fa fa-times-circle" style="color: red;"></i> Encerrar</a></li>
                                                    {{-- <li><a class="trash click"><i class="fa fa-trash text-danger"></i> Deletar</a></li> --}}
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach

                                    @foreach( $atual_classes as $classe )
                                    @if( $classe->status == 'B' )
                                    <tr class="data danger teste2" id="{{encrypt($classe->id)}}">
                                        <td>
                                            <a class="btn btn-default" href='{{ URL::to("classes/offers?t=".encrypt($classe->id)) }}'>
                                                <?= isset(str_split($classe->classe, 30)[1]) ? str_split($classe->classe, 30)[0]."..." : "$classe->classe_name $classe->classe" ?>
                                            </a>
                                            <span class="label label-danger">Bloqueada</span>
                                        </td>
                                        <td>{{ $classe->period }}</td>
                                        <td>{{ $classe->name }}</td>
                                        <td>
                                            <div class="col-md-12">
                                                <i class="pull-right fa fa-gears icon-default click" data-toggle="dropdown" aria-expanded="false"></i>
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                    <li><a class="classe-edit"><i class="fa fa-edit text-primary"></i> Editar</a></li>
                                                    <li><a class="change-status click" href="/classes/change-status" value="E"><i class="fa fa-times-circle" style="color: blue;"></i> Desbloquear</a></li>
                                                    {{-- <li><a class="trash click"><i class="fa fa-trash text-danger"></i> Deletar</a></li> --}}
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach

                                    @foreach( $atual_classes as $classe )
                                    @if( $classe->status == 'F' )
                                    <tr class="data teste3" id="{{encrypt($classe->id)}}">
                                        <td>
                                            <a class="btn btn-default" href='{{ URL::to("classes/offers?t=".encrypt($classe->id)) }}'>
                                                <?= isset(str_split($classe->classe, 30)[1]) ? str_split($classe->classe, 30)[0]."..." : "$classe->classe_name $classe->classe" ?>
                                            </a>
                                            <span class="label label-default">Encerrada</span>
                                        </td>
                                        <td>{{ $classe->period }}</td>
                                        <td>{{ $classe->name }}</td>
                                        <td>
                                            <div class="col-md-12">
                                                <i class="pull-right fa fa-gears icon-default click" data-toggle="dropdown" aria-expanded="false"></i>
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                    <li><a class="classe-edit"><i class="fa fa-edit text-primary"></i> Editar</a></li>
                                                    <li><a class="change-status click" href="/classes/change-status" value="E"><i class="fa fa-times-circle" style="color: blue;"></i> Reativar</a></li>
                                                    {{-- <li><a class="trash click"><i class="fa fa-trash text-danger"></i> Deletar</a></li> --}}
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
@include("modules.receiveClassesModal", ['school_year' => $school_year])
@include("modules.progressionClassesModal", ['previous_classes' => $previous_classes])
@include("offers.blockUnitModal")
@include("offers.unblockUnitModal")
@include("offers.createUnitModal")
<!--@include("offers.deleteUnitModal")-->

@stop