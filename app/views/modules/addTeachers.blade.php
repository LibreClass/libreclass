@extends('social.master')

@section('css')
@parent
{{ HTML::style('css/blocks.css') }}
@stop

@section('js')
@parent
{{ HTML::script('js/blocks.js') }}
{{ HTML::script('js/teacher.js') }}
{{ HTML::script('js/validations/modulesAddTeachers.js') }}
@stop

@section('body')
@parent

<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">
    <div id="block">
      
      <div class="block">

        <div class="row">
          <div class="col-sm-9">
            <h3 class="text-blue"><i class="icon-teacher"></i> <b>Meus Professores</b></h3>
          </div>
          <div class="col-sm-3">
            <button id="new-block" class="btn btn-primary btn-block pull-right"><i class="fa fa-plus"></i> Novo Professor</button>
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
                {{ Form::text("search", Input::get("search"), ["class" => "form-control input-lg"] ) }}
                <span class="input-group-btn"><button id="submit-teacher" class="btn btn-lg btn-primary"><i class="fa fa-lg fa-search"></i></button></span>
              </div>
            </div>
            {{ Form::close() }}
          </div>
            
        <div class="col-md-12  table-responsive">
          @if(count($relationships) == 0)
             <h4 class="text-center">Não há professores cadastrados</h4>
          @else
            <table id="list-teacher" class="table table-hover table-condensed">
                <thead>
                  <tr>
                    <th>Inscrição</th>
                    <th>Nome</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                @foreach($relationships as $relationship)
                  <tr>
                    <td> <a href='{{ URL::to("user/profile?u=".Crypt::encrypt($relationship->id)) }}'>{{$relationship->enrollment }}</a></td>
                    <td>{{ $relationship->name }}</td>
                    <td class="text-right">
                      <div class="col-md-12 text-right">
                        <i class="fa fa-gears icon-default click" data-toggle="dropdown" aria-expanded="false"></i>
                        <ul class="dropdown-menu" role="menu" data="{{ Crypt::encrypt($relationship->id) }}" data-name="{{ $relationship->name }}">
                          <li><a class="course-edit click"><i class="fa fa-edit text-info"></i> Editar</a></li>
                          <li><a class="trash click"><i class="fa fa-unlink text-danger"></i> Desvincular</a></li>
                          @if($relationship->type == "M")
                            <li><a class="click invite-teacher"><i class="fa fa-key text-info"></i> Liberar Acesso</a></li>
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

                @for ( $i = $ini ; $i < $fim ; $i++ )
                  <li {{ ($i == $current ? "class='active'" : "") }} ><a href="{{ $i }}">{{ $i+1 }} {{ ($i == $current ? "<span class='sr-only'>(current)</span>" : "") }}</a></li>
                @endfor

                @if( $fim <= (int)($length/$block-1) )
                  <li><a href="{{ (int)(($length/$block-$fim)/2)+$fim }}">...</a></li>
                @endif
                @if( $fim <= (int)($length/$block) )
                  <li><a href="{{ (int)($length/$block) }}">{{ (int)($length/$block)+1 }}</a></li>
                @endif
              </ul>
            </nav>
            @endif
        </div>
      </div>
    </div>
  </div>         
    <div id="block-add" class="block visible-none">
      <div class="row">
        <div class="col-sm-10">
          <h3 id="title-discipline" class="text-blue"><b>Novo Professor</b></h3>
        </div>
        <div class="col-sm-2">
          <button id="btn-back" class="btn btn-default btn-block">Voltar</button>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-md-12">
          {{ Form::open(["id" => "new-teacher"]) }}
            {{ Form::hidden("teacher", null) }}
            <div class="form-group">
              {{ Form::label("enrollment", "Matrícula", ["class" => "control-label"]) }}
              <span class="help-block">Informe o nome completo do professor.</span>
              {{ Form::text("enrollment", null, ["class" => "form-control input-lg", "autofocus", "required"]) }}
            </div>
            <div class="form-group">
              {{ Form::label("name", "Nome", ["class" => "control-label"]) }}
              <span class="help-block">Informe o nome completo do professor.</span>
              {{ Form::text("name", null, ["class" => "form-control input-lg", "autofocus", "required"]) }}
            </div>
            <div class="form-group">
              {{ Form::label("course", "Curso", ["control" => "control-label"]) }}
              <span class="help-block">Selecione o curso ao qual o professor está vinculado.</span>
              {{ Form::select("course", $courses, null, ["class" => "form-control input-lg"]) }}
            </div>

            {{ Form::submit("Salvar", ["class" => "pull-right btn btn-lg btn-primary"]) }}
          {{ Form::close() }}


        </div>
      </div>
    </div>
  </div>

<div class="modal fade visible-none" id="invite-teacher-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Liberar Acesso ao Professor</h4>
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
  
  
  
<div class="visible-none">
{{ Form::open(["id" => "delete-discipline", "url" => url("/disciplines/delete")]) }}
  {{ Form::hidden("discipline", null) }}
{{ Form::close() }}
</div>

@stop
