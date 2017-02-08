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
{{ HTML::script('js/permissions.js') }}
{{ HTML::script('js/validations/socialCourses.js') }}
@stop

@section('body')
@parent

<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">

    <div class="block">
      <h3 class="text-blue"><b>Minha instituição</b></h3>
      <hr>
      <div class="row">
        <div class="col-md-12">
          <ul class="list-inline">
            <li role="presentation"><a href="#" class="btn btn-default">Dados</a></li>
            <li role="presentation"><a href="#" class="btn btn-default">Usuários</a></li>
          </ul>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 text-right">
          <button type="button" id="new-permission" data-toggle="modal" data-target="#modalNewUser" class="btn btn-primary bold"><i class="fa fa-plus fa-fw"></i>Novo usuário</button>
        </div>
      </div>

      <table class="table">
        <thead>
          <tr>
            <th>Usuário</th>
            <th>Email</th>
            <th>Tipo</th>
          </tr>
        </thead>
        <tbody class="text-sm">
          @foreach( $adminers as $adminer)
            <tr class="edit-permission" data="{{ $adminer->id }}">
              <td>{{ $adminer->name }}</td>
              <td>{{ $adminer->email }}</td>
              <td>
                @foreach($adminer->modules as $module)
                  {{ $module->name }}.
                @endforeach
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalNewUser" tabindex="-1" role="dialog" aria-labelledby="modalNewUser" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      {{ Form::open() }}
        {{-- Form::hidden("id") --}}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Usuário</h4>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              {{ Form::label("id", "Nome") }}
              <span class="help-block">Informe o nome do usuário</span>
              {{ Form::select("id", $listfriends, null, ["class" => "form-control", "required"]) }}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              {{ Form::label("email", "Email") }}
              <span class="help-block">Informe o email do usuário</span>
              {{ Form::email("email", null, ["class" => "form-control", "disabled"]) }}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              {{ Form::label("ctrl", "Tipo de conta") }}
              <span class="help-block">Escolha os tipos de acesso</span>
              @foreach($listmodules as $key => $value)
                {{ Form::checkbox("ctrl[]", $key) }} {{ $value }} <br/>
              @endforeach
              {{-- Form::select("ctrl[]", $listmodules, null, ["class" => "form-control", "multiple"]) --}}
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
    {{ Form::close() }}
    </div>
  </div>
</div>


@stop
