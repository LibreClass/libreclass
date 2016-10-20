@extends('social.master')

@section('css')
@parent
  {{ HTML::style('css/blocks.css') }}
  {{ HTML::style('css/config.css') }}
@stop

@section('js')
@parent
  {{ HTML::script('js/config.js') }}
  {{ HTML::script('https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places') }}
  {{ HTML::script('js/validations/usersConfig.js') }}
@stop

@section('body')
@parent

<div class="row">
  
  <div id="config" class="col-md-8 col-xs-12 col-sm-12">
    
    <div id="block-config" class="block">
      
      @if(Session::has("message"))
      <div class="alert alert-success" role="alert">{{ Session::get("message") }}</div>
      @endif
      
      <div class="row">
        <div class="col-md-6 col-xs-6">
          <h4 class="">CONFIGURAÇÕES</h4>
        </div>
        <div class="col-md-6 col-xs-6 alternate visible-none">
          <button id="btn-back" class="btn btn-default pull-right">Voltar</button>
        </div>
      </div>
      <br>

      <div class="row">
        <div class="col-md-12">
          <table class="table table-hover click">

            <tr class="block-config-item">
              <td>Foto</td>
              <td>{{ strlen($user->photo) ? HTML::image($user->photo, null, ["class" => "user-photo-2x"]) : "<span class='text-info'>Inserir Foto</span>"}}</td>
              <td></td>
            </tr>

            <tr class="visible-none">
              <td colspan="3">
                {{ Form::open(["url" => url("config/photo"), "enctype" => "multipart/form-data"]) }}
                <div class="form-group" style="margin-bottom: 5px;">
                  {{ Form::file("photo", ["class" => "form-control photo-button"]) }}
                </div>
                  <button class="btn btn-primary">Atualizar</button>
                {{ Form::close() }}
              </td>
            </tr>

            <tr class="block-config-item">
              <td>Nome</td>
              <td class="text-info">{{$user->name}}</td>
              <td></td>
            </tr>

            <tr class="visible-none">
              <td colspan="3">
                {{ Form::open(["url" => url("config/common"), "id" => "nameUpdate"]) }}
                <div class="form-group">
                  {{ Form::text("name", $user->name, ["class" => "form-control"], "required") }}
                </div>
                  <button class="btn btn-primary">Atualizar</button>
                {{ Form::close() }}
              </td>
            </tr>

            <!-- <tr class="block-config-item"> -->
            <tr>
              <td>Email</td>
              <!-- <td class="text-info">{{-- $user->email --}}</td> -->
              <td>{{$user->email}}</td>
              <td></td>
            </tr>

            <!-- <tr class="visible-none">
              <td colspan="3">
                {{-- Form::open(["url" => url("config/email"), "id" => "emailUpdate"]) --}}
                <div class="form-group">
                  {{-- Form::text("email", $user->email, ["class" => "form-control"]) --}}
                </div>
                  <button class="btn btn-primary">Atualizar</button>
                {{-- Form::close() --}}
              </td>
            </tr> -->

            @if( $user->cadastre == "N" )
              <tr class="block-config-item">
                <td>Senha</td>
                <td class="text-info">{{ "*******" }}</td>
                <td></td>
              </tr>

              <tr class="visible-none">
                <td colspan="3">
                  {{ Form::open(["url" => url("config/password"), "id" => "passwordUpdate"]) }}
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        {{ Form::label("password", "Senha Atual") }}
                        {{ Form::password("password", ["class" => "form-control", "id" => "password"]) }}
                      </div>
                      <div class="form-group">
                        {{ Form::label("newpassword", "Nova Senha") }}
                        {{ Form::password("newpassword", ["class" => "form-control"]) }}
                      </div>
                        <button class="btn btn-primary">Atualizar</button>
                    </div>
                  </div>
                  {{ Form::close() }}
                </td>
              </tr>
            @endif

            <tr class="block-config-item">
              <td>Data de Nascimento</td>
              <td class="text-info">{{ $user->birthdate ? date("d / m / Y", strtotime($user->birthdate)) : "Inserir" }}</td>
              <td></td>
            </tr>

            <tr class="visible-none">
              <td colspan="3">
                {{ Form::open(["url" => url("config/birthdate")]) }}
                <div class="row">

                  <div class="col-md-8 col-sm-8">
                    <div class="form-inline">
                      <div class="form-group">
                        {{ Form::selectRange("birthdate-day", 1, 31, date("d", strtotime($user->birthdate)),["class" => "form-control"]) }}
                        {{ Form::selectRange("birthdate-month", 1, 12, date("m", strtotime($user->birthdate)), ["class" => "form-control"]) }}
                        {{ Form::selectRange("birthdate-year", date("Y"), date("Y")-100, date("Y", strtotime($user->birthdate)), ["class" => "form-control"]) }}
                      </div>
                    </div>
                    <br>
                    <button class="btn btn-primary">Atualizar</button>
                  </div>
                </div>

                {{ Form::close() }}
              </td>
            </tr>

            <tr class="block-config-item">
              <td>Sexo</td>
              <td class="text-info">{{$user->gender == "M" ? "Masculino" : ($user->gender == "F" ? "Feminino" : "Inserir")}}</td>
              <td></td>
            </tr>

             <tr class="visible-none">
              <td colspan="3">
                {{ Form::open(["url" => url("config/commonselect")]) }}
                <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                        {{ Form::select("gender", $select["gender"], $user->gender, ["class" => "form-control"]) }}
                      </div>
                    <button class="btn btn-primary">Atualizar</button>
                  </div>
                </div>
                {{ Form::close() }}
              </td>
            </tr>

            <tr class="block-config-item">
              <td>Formação</td>
              <td class="text-info">{{$user->formation ? $select["formation"][$user->formation] : "Inserir"}}</td>
              <td></td>
            </tr>

            <tr class="visible-none">
              <td colspan="3">
                {{ Form::open(["url" => url("config/commonselect")]) }}
                <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                        {{ Form::select("formation", $select["formation"], $user->formation, ["class" => "form-control"]) }}
                      </div>
                    <button class="btn btn-primary">Atualizar</button>
                  </div>
                </div>
                {{ Form::close() }}
              </td>
            </tr>

            <tr class="block-config-item">
              <td>Curso</td>
              <td class="text-info">{{$user->course or "Inserir"}}</td>
              <td></td>
            </tr>

             <tr class="visible-none">
              <td colspan="3">
                {{ Form::open(["url" => url("config/common"), "id" => "courseUpdate"]) }}
                <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                        {{ Form::text("course", $user->course, ["class" => "form-control", "required"]) }}
                      </div>
                    <button class="btn btn-primary">Atualizar</button>
                  </div>
                </div>
                {{ Form::close() }}
              </td>
            </tr>

            <tr class="block-config-item">
              <td>Instituição</td>
              <td class="text-info" id="institution-description">{{$user->institution or "Inserir"}}</td>
              <td></td>
            </tr>

             <tr class="visible-none">
              <td colspan="3">
                {{ Form::open(["url" => url("config/common"), "id" => "institutionUpdate"]) }}
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      {{ Form::text("institution", $user->institution, ["class" => "form-control", "required"]) }}
                    </div>
                    {{ Form::submit('Atualizar', ["class" => "btn btn-primary"]) }}
                  </div>
                </div>
                {{ Form::close() }}
              </td>
            </tr>


            <!-- <tr class="block-config-item"> -->
            <tr>
              <td>Tipo de Conta</td>
              <!-- <td class="text-info">{{-- $user->type ? $select["type"][$user->type] : "" --}}</td> -->
              <td>{{ $user->type ? $select["type"][$user->type] : "" }}</td>
              <td></td>
            </tr>

            <!-- <tr class="visible-none">
              <td colspan="3">
                {{-- Form::open(["url" => url("config/type")]) --}}
                <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                        {{-- Form::select("type", $select["type"], $user->type, ["class" => "form-control"]) --}}
                      </div>
                    <button class="btn btn-primary">Atualizar</button>
                  </div>
                </div>
                {{-- Form::close() --}}
              </td>
            </tr> -->


            <tr id="block-map" class="block-config-item">
              <td>Localização</td>
              <td class="text-info">{{ $user->idCity ? $user->printLocation() : "" }}</td>
              <td></td>
            </tr>

            <tr class="visible-none">
              <td colspan="3">
                <input id="pac-input" class="controls form-control" type="text" placeholder="Informe sua cidade">
                <div id="map-canvas"></div>
              </td>
            </tr>


          </table>
        </div>
      </div>
    </div>



  </div>
</div>


@stop
