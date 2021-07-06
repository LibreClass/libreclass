	<div class="modal fade" id="modalPeriods" tabindex="-1" data-backdrop="static" role="Modal New Periods" aria-labelledby="modalPeriodsLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modalPeriodsLabel"><b>Ano</b></h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<form action="/course/periods" id="add-periods" class="registerForm">
							<label>Curso</label>
							<select name="course" class="form-control" placeholder="Escolha um curso">
								@foreach($listcourses as $i => $course)
									<option value="{{ $i }}">{{ $course }}</option>
								@endforeach
							</select>
						</form>
					</div>
						<div id="list-periods" class="list-unstyled">
						</div>

					 <form class="row input-edit-period">
						<div class="col-xs-4">
							<input type="text" id="input-new-period" name="period" class="form-control input-border-none" placeholder="Novo ano">
						</div>
						<div class="col-xs-2">
							<button class="btn btn-primary btn-sm">Salvar</button>
						</div>
					</form>

					<hr>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default reload-page" data-dismiss="modal">Sair</button>
				</div>
			</div>
		</div>
	</div>
