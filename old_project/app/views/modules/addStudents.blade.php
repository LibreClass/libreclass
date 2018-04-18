@extends('social.master')

@section('css')
@parent
{{ HTML::style('css/blocks.css') }}
@stop

@section('js')
@parent
{{ HTML::script('js/blocks.js') }}
{{ HTML::script('js/student.js') }}
{{ HTML::script('js/user.js') }}
{{ HTML::script('js/validations/modulesAddStudents.js') }}
@stop

@section('body')
@parent

<div class="row" id="view-student">
  <div class="col-md-8 col-xs-12 col-sm-12">
    <div id="block">

      <div class="block">

        <div class="row">
          <div class="col-md-6 col-xs-6">
            <h3 class="text-blue"><i class="fa fa-users"></i> <b>Meus Alunos</b></h3>
          </div>
          <div class="col-md-6 col-xs-6">
            <button id="block-new-student" class="btn btn-primary pull-right"><b><i class="fa fa-plus"></i> Novo Aluno</b></button>
          </div>
        </div>
      </div>

      <div class="block">
        <div class="row">
          <div class="col-md-12">
            {{ Form::open(["method" => "GET", "id" => "find-student"]) }}
            <div class="form-group">
              {{ Form::hidden("current", $current) }}
              {{ Form::label("search", "Procurar", ["class" => "control-label text-md"] ) }}
              <span class="help-block text-muted">Faça a busca informando parte do nome desejado ou número da matrícula.</span>
              <div class="input-group col-md-12">
                {{ Form::text("search", Input::get("search"), ["class" => "form-control"] ) }}
                <span class="input-group-btn"><button id="submit-student" class="btn btn-primary"><i class="fa fa-lg fa-search"></i></button></span>
              </div>
            </div>
            {{ Form::close() }}
          </div>
          <div class="col-md-12  table-responsive">
            @if(count($relationships) == 0)
              <h4 class="text-center">Não há alunos cadastrados</h4>
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
                  <tr data-relationship-id="{{Crypt::encrypt($relationship->id)}}" class="student-item">
                    <td><a href='{{ URL::to("user/profile-student?u=".Crypt::encrypt($relationship->id)) }}'>{{$relationship->enrollment }}</a></td>
                    <td><a href='{{ URL::to("user/profile-student?u=".Crypt::encrypt($relationship->id)) }}'>{{ $relationship->name }}</a></td>
										<td class="text-right">
                      <div class="col-md-12 text-right">
                        <i class="fa fa-gears icon-default click" data-toggle="dropdown" aria-expanded="false"></i>
												<ul class="dropdown-menu data" role="menu">
                        	<li><a class="new-student" edit title="Editar aluno"><i class="fa fa-edit text-info"></i> Editar</a></li>
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
        <div class="col-md-6 col-xs-6">
          <h4 id="title-discipline">Novo Aluno</h4>
        </div>
        <div class="col-md-6 col-xs-6">
          <button id="btn-back" class="btn btn-default pull-right">Voltar</button>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="block-new-student" hidden>
          {{ Form::open(["id" => "new-student"]) }}
					{{ Form::text("student_id", null, ['class' => "hidden"]) }}
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  {{ Form::label("enrollment", "*Inscrição") }}
                  {{ Form::text("enrollment", null, ["class" => "form-control", "autofocus", "required"]) }}
                </div>
              </div>
              <div class="col-md-6 col-xs-6">
                <div class="form-group">
                  {{ Form::label("date-day", "Data de Nascimento: ")}}
                  <div class="form-inline">
                    {{ Form::selectRange("date-day", 1, 31, null, ["class" => "form-control"]) }}
                    {{ Form::selectRange("date-month", 1, 12, null, ["class" => "form-control"]) }}
                    {{ Form::selectRange("date-year", date("Y"), date("Y")-100, null, ["class" => "form-control"]) }}
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              {{ Form::label("name", "*Nome") }}
              {{ Form::text("name", null, ["class" => "form-control", "required"]) }}
            </div>
						<div class="row">
							<div class="col-xs-12 col-sm-4">
								<div class="form-group">
									{{ Form::label("gender", "*Sexo") }}
									{{ Form::select("gender", ['M'=> 'Masculino', 'F'=> 'Feminino'], null, ["class" => "form-control", 'required']) }}
								</div>
							</div>
							<div class="col-xs-12 col-sm-8">
								<div class="form-group">
									{{ Form::label("email", "Email") }}
									{{ Form::email("email", null, ["class" => "form-control"]) }}
								</div>
							</div>
						</div>
            <div class="form-group">
              {{ Form::label("course", "Curso") }}
              {{ Form::select("course", $courses, null, ["class" => "form-control"]) }}
            </div>

          	<div class="row">
          		<div class="col-xs-6">
            {{ Form::submit("Confirmar", ["class" => "btn btn-primary"]) }}
							</div>
							<!-- <div class="col-xs-6 text-right">
								<a href="" class="add-censo">Adicionar informações do censo escolar</a>
							</div> -->
						</div>
          {{ Form::close() }}

          </div>

          <div class="block-search-student">
            {{ Form::open(["id" => "search-student"]) }}
              {{ Form::label("search", "*Nome ou email") }}
              <span class="help-block">Localize um aluno já existente na base de dados do Libreclass</span>
              <div class="row">
                <div class="col-md-10">
                  <div class="form-group">
                    {{ Form::text("search", null, ["class" => "form-control", "required", "autocomplete" => "off"]) }}
                  </div>
                </div>
                <div class="col-md-2">
                  {{ Form::submit("Buscar", ["class" => "btn btn-primary btn-block"]) }}
                </div>
              </div>

            {{ Form::close() }}
            <div class="text-center new-student">
              <span class="text-primary click">Não encontrei, desejo adicionar um novo aluno</span>
            </div>
          <ul class="list-unstyled result list-user" t="student" id="result-search-student"></ul>
          </div>




        </div>
      </div>
    </div>
  </div>
</div>

<div class="visible-none">
{{ Form::open(["id" => "delete-discipline", "url" => url("/disciplines/delete")]) }}
  {{ Form::hidden("discipline", null) }}
{{ Form::close() }}
</div>

@stop
