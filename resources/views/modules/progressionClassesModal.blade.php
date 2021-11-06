<div class="modal fade" id="modalProgressionClasses" tabindex="-1" role="Modal Add Class" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-blue"><b><i class="fa fa-share fa-fw text-muted"></i> Progressão de alunos</b></h3>
			</div>
			<div class="modal-body">
				{{ Form::open(["id" => "formImportStudent"]) }}
				<input name="classe_id" type="text" hidden />
				<p>
					Nesta área é possível importar alunos das turmas do {{ strtolower(session('period.singular')) }} anterior para essa turma<br />
				</p>
				<br />
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<label>Selecione a turma</label>
							<select name="previous_classe_id" class="form-control ev-get-students">
								<option disabled hidden selected>Selecione uma turma</option>
								@foreach($previous_classes as $classe)
									<option value="{{ encrypt($classe->id) }}"> {{ $classe->name .' - '. $classe->period .' - '. $classe->classe_name}}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>

				<div class="block_list_students" hidden>
					<p><strong>Alunos</strong></p>
					<ul class="list-group list-attends">
					</ul>

					<p class="text-muted">
						Confira todos os dados antes de salvar.
					</p>
					<div class="text-right">
						<button type="button" class="btn btn-primary ev-save-import"><i class="fa fa-save"></i> Confirmar</button>
					</div>
				</div>

				</form>
			</div>
		</div>
	</div>
</div>
