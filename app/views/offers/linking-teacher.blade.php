@section('js')
@parent

@stop

<div class="modal fade" id="modalTeacherOffer" tabindex="-1" role="Modal Add Teacher Offer" aria-labelledby="modalTeacherOffer" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      {{ Form::open(["url" => URL::to("/classes/offers/teacher"), "id" => "formLinkingTeacher"]) }}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-blue"><b><i class="fa fa-link"></i> Vincular Professor</b></h3>
      </div>
      <div class="modal-body">


        {{ Form::hidden("offer", null) }}
        {{ Form::hidden("prev", URL::full() ) }}
        {{ Form::label("teacher", "Professor", ["class" => "control-label"] ) }}
        <span class="help-block text-muted">Localize o professor</span>

        <div class="form-group panel panel-form-multiple panel-body" id="find-teacher">

          <div class="row">
            <div class="col-md-12">
              <div class="selected-teacher">
                <ul class="list-inline insert-sheriff">
                  <li class="label label-select-multiple model">
                    <div class="label-image"></div>
                    <span class="label-name"></span>
                    <span class="label-remove text-muted"><i class="fa fa-fw fa-remove click"></i></span>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <div class="dropdown">
            {{ Form::text("teacher", null, ["class" => "form-control input-border-none input-sheriff", "id"=>"dropdownTeacher", "data-toggle" => "dropdownX", "autocomplete" => "off"] ) }}
            <ul class="dropdown-menu list-sheriff" role="menu" aria-labelledby="dropdownTeacher">
              <li role="presentation" class="model">
                <a role="menuitem" tabindex="-1" href="#">
                  <div class="label-image"></div>
                  <span class="label-name"></span>
                </a>
              </li>
            </ul>
          </div>

        </div>

        <div class="form-group">
          {{ Form::label("classroom", "Sala de Aula",  ["class" => "control-"])}}
          <span class="help-block text-muted">Informe o nome da sala de aula onde será lecionada a disciplina.</span>
          {{ Form::text("classroom", null,  ["class" => "form-control"])}}
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              {{ Form::label("day_period", "Turno",  ["class" => "control-label"])}}
              <span class="help-block text-muted">Informe o período do dia da oferta.</span>
              {{ Form::select("day_period", ["M" => "Matutino", "V" => "Vespertino", "N" => "Noturno"], null, ["class" => "form-control", "required"])}}
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              {{ Form::label("maxlessons", "Número máximo de aulas",  ["class" => "control-label"])}}
              <span class="help-block text-muted">Número máximo de aulas da oferta.</span>
              {{ Form::number("maxlessons", null,  ["class" => "form-control", "required"])}}
            </div>
        </div>


        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button class="pull-right btn btn-primary">Concluir</button>
      </div>
      {{ Form::close() }}
    </div>
  </div>
</div>
