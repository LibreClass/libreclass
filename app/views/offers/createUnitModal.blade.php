<div class="modal fade" id="modalCreateUnit" tabindex="-1" role="Modal Create Unit" aria-labelledby="modalCreateUnit" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title text-blue"><b><i class="fa fa-plus text-primary fa-fw"></i> Criar nova unidade</b></h3>
        </div>

        {{ Form::open(["id" => "formCreateUnit", "url" => "/classes/create-units"]) }}
        <div class="modal-body">

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">

              <div class="form-group">
                {{ Form::label("course", "Curso") }}
                <span class="help-block text-muted">Selecione o curso que deseja criar a unidade.</span>
                {{ Form::select("course", [], null, ["class" => "form-control input-lg"]) }}
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <span class="text-danger"> * Essa ação é irreversível.</span>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <div class="form-submit">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-plus fa-fw"></i> Criar</button>
          </div>
          <div class="form-process text-center" hidden>
            <i class="fa fa-spin fa-spinner fa-lg text-muted"></i> Processando.
          </div>
        </div>
        {{ Form::close() }}
      </div>
    </div>
  </div>
