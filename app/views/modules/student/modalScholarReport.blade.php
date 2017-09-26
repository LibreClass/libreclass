  <div class="modal fade" id="modalScholarReport" tabindex="-1" role="Modal Info Invite" aria-labelledby="modalInvite" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
				<form id="form-scholar-report" data-url="{{ URL::to("/user/scholar-report") }}">

	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	          <h3 class="modal-title"><b><i class="fa fa-file-o fa-fw"></i> Gerar boletim escolar</b></h3>
	        </div>

	        <div class="modal-body">
	          <div class="row">
	            <div class="col-xs-12">
	              <div class="form-group">
	                <h4>Selecione o período que deseja obter o boletim</h4>
	                {{ Form::select("class", $listclasses, null, ["class" => "form-control", "id" => "class-modal-change"]) }}
	              </div>
	            </div>
	          </div>
						<br />
						<div>
							Selecione as unidades
						</div>
						<div class="row">
							<div class="col-xs-3">
								<div class="checkbox">
									<label><input type="checkbox" name="unit_value[]" value="1" checked>Unidade 1</label>
								</div>
							</div>
							<div class="col-xs-3">
								<div class="checkbox">
									<label><input type="checkbox" name="unit_value[]" value="2" checked>Unidade 2</label>
								</div>
							</div>
							<div class="col-xs-3">
								<div class="checkbox">
									<label><input type="checkbox" name="unit_value[]" value="3" checked>Unidade 3</label>
								</div>
							</div>
							<div class="col-xs-3">
								<div class="checkbox">
									<label><input type="checkbox" name="unit_value[]" value="4" checked>Unidade 4</label>
								</div>
							</div>
						</div>
	        </div>

	        <div class="modal-footer">
	          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	          <button type="submit" class="btn btn-primary"> Gerar</button>
	        </div>
				</form>
      </div>
    </div>
  </div>
