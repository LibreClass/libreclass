@extends('social.master')

@section('css')
@parent
  <link media="all" type="text/css" rel="stylesheet" href="/css/blocks.css">
  <link media="all" type="text/css" rel="stylesheet" href="/css/config.css">
  <style>
#map { width: 100%; }
</style>
@stop

@section('js')
@parent
  <script src="/js/config.js"></script>
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBVmdz-iIJpd-TisFvAg4qIU9WVqRVvVbk&v=3.exp&libraries=places"></script> 
  <script src="/js/validations/usersConfig.js"></script>

@stop

@section('body')
@parent

<div class="row">

  <div id="config" class="col-md-8 col-xs-12 col-sm-12">

    <div id="block-config" class="block">

      @if (session("message"))
        <div class="alert alert-success" role="alert">{{ session("message") }}</div>
      @endif

      <div class="row">
        <div class="col-md-6 col-xs-6">
          <h4 >CONFIGURAÇÕES</h4>
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
              <td>
                @if (strlen($user->photo))
                  {{ HTML::image($user->photo, null, ["class" => "user-photo-2x"]) }}
                @else
                  <span class='text-info'>Inserir Foto</span>
                @endif
              </td>
              <td></td>
            </tr>

            <tr class="visible-none">
              <td colspan="3">
                {{ Form::open(["url" => url("config/photo"), "enctype" => "multipart/form-data"]) }}
                <div class="form-group" style="margin-bottom: 5px;">
                  {{ Form::file("photo", ["class" => "form-control photo-button"]) }}
                </div>
                  <button class="btn btn-primary">Atualizar</button>
                </form>
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
                </form>
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
                <td class="text-info">*******</td>
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
                  </form>
                </td>
              </tr>
            @endif

            @if($user->type == "I")
            <tr class="block-config-item">
              <td>Código da UEE</td>
              <td class="text-info">{{ $user->uee ?? "Inserir" }}</td>
              <td></td>
            </tr>

            <tr class="visible-none">
              <td colspan="3">
                {{ Form::open(["url" => url("config/uee"), "id" => "ueeUpdate"]) }}
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      {{ Form::text("uee", $user->uee, ["class" => "form-control", "required"]) }}
                    </div>
                    {{ Form::submit('Atualizar', ["class" => "btn btn-primary"]) }}
                  </div>
                </div>
                </form>
              </td>
            </tr>
          @endif

          @if($user->type != "I")
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
                        {{ Form::selectRange("birthdate-day", 1, 31, (int) date("d", strtotime($user->birthdate)),["class" => "form-control"]) }}
                        {{ Form::selectRange("birthdate-month", 1, 12, (int) date("m", strtotime($user->birthdate)), ["class" => "form-control"]) }}
                        {{ Form::selectRange("birthdate-year", date("Y"), date("Y")-100, date("Y", strtotime($user->birthdate)), ["class" => "form-control"]) }}
                      </div>
                    </div>
                    <br>
                    <button class="btn btn-primary">Atualizar</button>
                  </div>
                </div>

                </form>
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
                </form>
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
                </form>
              </td>
            </tr>

            <tr class="block-config-item">
              <td>Curso</td>
              <td class="text-info">{{$user->course ?? "Inserir"}}</td>
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
                </form>
              </td>
            </tr>

            <tr class="block-config-item">
              <td>Instituição</td>
              <td class="text-info" id="institution-description">{{$user->institution ?? "Inserir"}}</td>
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
                </form>
              </td>
            </tr>
          @endif

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
          @if($user->type == "I")
            <tr class="block-config-item">
              <td>Endereço</td>
              <td class="text-info" id="institution-description">{{ $user->street ?? "Inserir" }}</td>
              <td></td>
            </tr>

            <tr class="visible-none">
              <td colspan="3">
                {{ Form::open(["url" => url("config/street"), "id" => "streetUpdate"]) }}
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      {{ Form::text("street", $user->street, ["class" => "form-control", "required"]) }}
                    </div>
                    {{ Form::submit('Atualizar', ["class" => "btn btn-primary"]) }}
                  </div>
                </div>
                </form>
              </td>
            </tr>
          @endif

            <tr id="block-map" class="block-config-item">
              <td>Localização</td>
              <td class="text-info">{{ $user->city_id ? $user->printLocation() : "" }}</td>
              <td></td>
            </tr>
            <tr class="visible-none">
              <td colspan="3">
                <input id="pac-input" class="controls form-control" type="text" placeholder="Informe sua cidade">
              <div id="map-canvas"> 

                
            </tr>


          </table>
        </div>
      </div>
    </div>



  </div>
</div>


@stop
