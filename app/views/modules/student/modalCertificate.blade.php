  <div class="modal fade" id="modalCertificate" tabindex="-1" role="Modal Info Certificate" aria-labelledby="modalCertificate" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title text-blue" id="modalAddTeacher"><b><i class="fa fa-file-o fa-fw"></i> Atestado</b></h4>
        </div>
        {{ Form::open(["id" => "formCertificate", "url" => URL::to("/user/attest")]) }}
        <div class="modal-body">

          {{ Form::hidden("student", Crypt::encrypt($profile->id)) }}
          <div class="row">
            <div class="col-sm-6 col-xs-12">
              {{ Form::label("date-day", "Data", ["class" => "control-label"])}}
              <span class="help-block text-muted">Informe o início da validade do atestado.</span>
              <div class="form-inline">
                <div class="form-group">

                  {{ Form::selectRange("date-day", 1, 31, date("d"), ["class" => "form-control", "auto-focus"]) }}
                  {{ Form::selectRange("date-month", 1, 12, date("m"), ["class" => "form-control"]) }}
                  {{ Form::selectRange("date-year", date("Y"), date("Y")-10, date("Y"), ["class" => "form-control"]) }}
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-12">
              <div class="form-group">
                {{ Form::label("days", "Quantidade de dias", ["class" => "control-label"]) }}
                <span class="help-block">Número de dias válidos para atestado.</span>
                {{ Form::number("days", null, ["class" => "form-control input-1x", "required", "min" => "1"]) }}
              </div>
            </div>
          </div>
          <div class="form-group">
            {{ Form::label("description", "Descrição", ["class" => "control-label"]) }}
            <span class="help-block">Descreva o motivo do atestado.</span>
            {{ Form::textarea("description", null, ["class" => "form-control", "autofocus", "required", "size" => "30x5"]) }}
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save fa-fw"></i> Salvar</button>
        </div>
        {{ Form::close() }}
      </div>
    </div>
  </div>
