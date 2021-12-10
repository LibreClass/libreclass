@extends('social.master')

@section('css')
@parent
<link media="all" type="text/css" rel="stylesheet" href="/css/blocks.css">
<link media="all" type="text/css" rel="stylesheet" href="/css/datatables.min.css">
@stop

@section('js')
@parent
<script src="/js/blocks.js"></script>
<script src="/js/teacher.js"></script>
<script src="/js/validations/modulesAddTeachers.js"></script>
<script src="/js/datatables.js"></script>
@stop

@section('body')
@parent

<div class="row">
    <div class="col-md-8 col-xs-12 col-sm-12">
        <div id="block">

            <div class="block">
                <div class="f-container f-align-center">
                    <div class="f-grow-3">
                        <h3 class="text-blue"><i class="icon-teacher"></i> <b>Professores</b></h3>
                    </div>
                    <div class="f-grow-1 text-right">
                        <lc-button id="new-teacher"> Adicionar </lc-button>
                    </div>
                </div>
            </div>

            <div class="block">
                <div class="row">
                    <div class="col-md-12">
                        {{ Form::open(["method" => "GET", "id" => "find-teacher"]) }}
                        <div class="form-group">
                            {{ Form::hidden("current", $current) }}
                            {{ Form::label("search", "Procurar", ["class" => "text-md control-label"] ) }}
                            <span class="help-block text-muted">Faça a busca informando parte do nome desejado ou número da matrícula.</span>
                            <div class="input-group col-md-12">
                                {{ Form::text("search", request()->get("search"), ["class" => "form-control"] ) }}
                                <span class="input-group-btn"><button id="submit-teacher" class="btn btn-primary"><i class="fa fa-lg fa-search"></i></button></span>
                            </div>
                        </div>
                        </form>
                    </div>

                    <div class="col-md-12  table-responsive">
                        @if(count($relationships) == 0)
                        <h4 class="text-center">Não há professores cadastrados</h4>
                        @else
                        <table id="list-teacher" class="table table-hover table-condensed order">
                            <thead>
                                <tr>
                                    <th class="arrow">Inscrição</th>
                                    <th class="arrow">Nome</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($relationships as $relationship)
                                <tr>
                                    <td> <a href='{{ url("user/profile-teacher?u=".encrypt($relationship->id)) }}'>{{$relationship->enrollment }}</a></td>
                                    <td>{{ $relationship->name }}</td>
                                    <td class="text-right">
                                        <div class="col-md-12 text-right">
                                            <i class="fa fa-gears icon-default click" data-toggle="dropdown" aria-expanded="false"></i>

                                            <ul class="dropdown-menu data" role="menu" data="{{ encrypt($relationship->id) }}" data-name="{{ $relationship->name }}">
                                                @if($relationship->type == "M")
                                                <li><a class="edit-teacher click" title="Edita informações do professor"><i class="fa fa-edit text-info"></i> Editar</a></li>
                                                @endif
                                                <li><a class="edit-register-teacher click" title="Edita matrícula do professor"><i class="fa fa-edit text-info"></i> Editar matrícula</a></li>
                                                <li><a class="trash click" title="Excluir relacionamento do professor com a instituição"><i class="fa fa-unlink text-danger"></i> Excluir</a></li>
                                                @if($relationship->type == "M")
                                                <li><a class="click invite-teacher" title="Liberar acesso do professor às ofertas da instituição"><i class="fa fa-key text-info"></i> Liberar Acesso</a></li>
                                                @endif
                                            </ul>

                                        </div>
                                        <!--<i class="fa fa-pencil icon-default"></i> <i class="fa fa-trash icon-default"></i>-->
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                        <nav class="text-center">
                            <ul id="pagination" class="pagination pagination-sm">
                                <?php
                  $ini = $current < 5 ? 0 : $current-5;
                  $fim = $current+6 > $length/$block ? $length/$block : $current+6;
                ?>
                                @if( $ini > 0 )
                                <li><a href="0">1</a></li>
                                @endif
                                @if( $ini > 1 )
                                <li><a href="{{ (int)(($ini)/2)+($ini)%2 }}">...</a></li>
                                @endif

                                @for ( $i = $ini ; $i < $fim ; $i++ ) <li {{ ($i == $current ? "class='active'" : "") }}><a href="{{ $i }}">{{ $i+1 }} {!! ($i == $current ? "<span class='sr-only'>(current)</span>" : "") !!}</a></li>
                                    @endfor

                                    @if( $fim <= (int)($length/$block-1) ) <li><a href="{{ (int)(($length/$block-$fim)/2)+$fim }}">...</a></li>
                                        @endif
                                        @if( $fim <= (int)($length/$block) ) <li><a href="{{ (int)($length/$block) }}">{{ (int)($length/$block)+1 }}</a></li>
                                            @endif
                            </ul>
                        </nav>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade visible-none" id="invite-teacher-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Liberar acesso ao professor</h4>
                </div>
                <div class="modal-body">
                    <p><b>Professor: </b><span id="name-invite-teacher"></span></p>
                    {{ Form::open(["id" => "form-invite-teacher", "url" => "/user/invite"]) }}
                    {{ Form::hidden("teacher", null) }}
                    {{ Form::label("email", "Email")}}
                    {{ Form::email("email", null, ["class" => "form-control"]) }}
                </div>
                <div class="modal-footer">
                    {{ Form::reset("Cancelar", ["class" => "btn btn-default", "data-dismiss" => "modal"])}}
                    <button type="confirm" class="btn btn-primary">Enviar</button>
                </div>
                {{ Form::close()}}
            </div>
        </div>
    </div>

    @include("modules.addTeachersModal")
    @include("modules.editRegisterTeacher")

    <div class="visible-none">
        {{ Form::open(["id" => "delete-discipline", "url" => url("/disciplines/delete")]) }}
        {{ Form::hidden("discipline", null) }}
        </form>
    </div>

    @stop