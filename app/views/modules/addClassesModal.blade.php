<div class="modal fade" id="modalAddClass" tabindex="-1" role="Modal Add Class" aria-labelledby="modalAddClass" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title text-blue" id="modalAddClass"><b><i class="icon-classes"></i> Turma</b></h3>
      </div>

      {{ Form::open([ "url" => URL::to("classes/new"), "id" => "formAddClass"]) }}
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
              {{ Form::label("period", "Curso/Período") }}
              <span class="help-block text-muted">Selecione o curso e período para a turma</span>
              {{ Form::select("period", $listPeriod, null, ["class" => "form-control input-lg"]) }}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-6">
            <div class="form-group">
              {{ Form::label("class", "Período Letivo", ["class" => "control-label"]) }}
              <span class="help-block text-muted">Informe o período letivo que pertence essa turma. Ex: 2015, 2015.1S</span>
              {{ Form::text("class", null, ["class" => "form-control input-lg"]) }}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-6">
            <div class="form-group">
              <span class="help-block text-muted">Informe um nome para identificar a turma. Ex: 1º Ano A, 2º MEDIO A - Vespertino.</span>
              {{ Form::text("name", null, ["class" => "form-control input-lg"]) }}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div id="block-offer-discipline"></div>
            <br>
          </div>
        </div>


        <p class="text-muted">
          Confira todos os dados antes de salvar.
        </p>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary" id="btnAddClass"><i class="fa fa-save"></i> Salvar</button>
      </div>
      {{ Form::close() }}
    </div>
  </div>
</div>

<div class="modal fade" id="modalEditClass" tabindex="-1" role="Modal Edit Class" aria-labelledby="modalEditClass" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title text-blue"><b><i class="icon-classes"></i> Editar Turma</b></h3>
      </div>

      {{ Form::open([ "url" => URL::to("classes/edit"), "id" => "formEditClass"]) }}
      <div class="modal-body">
        {{ Form::hidden("classId", null) }}
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-6">
            <div class="form-group">
              {{ Form::label("class", "Nome da turma", ["class" => "control-label"]) }}
              <span class="help-block text-muted">Informe um nome para identificar a turma. Ex: 2015.1.</span>
              {{ Form::text("class", null, ["class" => "form-control input-lg"]) }}
            </div>
          </div>
        </div>
				<div class="row">
					<div class="col-xs-12">
						<div class="EditClass-list-disciplines">

						</div>
					</div>
				</div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary" id="btnAddClass"><i class="fa fa-save"></i> Salvar</button>
      </div>
      {{ Form::close() }}
    </div>
  </div>
</div>
