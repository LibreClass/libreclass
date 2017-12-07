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
						<div class="row">
	            <div class="col-xs-12">
	              <div class="form-group">
	                <h4>Curso</h4>
									<select name="course" class="form-control" id="select-scholar-report-course">
										@foreach($courses as $course)
											<option value="{{$course->id}}" quant-unit="{{$course->quant_unit}}">{{$course->name}}</option>
										@endforeach
									</select>
	              </div>
	            </div>
	          </div>
						<br />
						<div>
							Selecione as unidades
						</div>
						<div class="row list-units-course">

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
