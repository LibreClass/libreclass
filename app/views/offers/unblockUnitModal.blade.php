<div class="modal fade" id="modalUnblockUnit" tabindex="-1" role="Modal Unblock Unit" aria-labelledby="modalUnblockUnit" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title text-blue"><b><i class="fa fa-unlock text-muted fa-fw"></i> Desbloquear Unidade</b></h3>
			</div>

			{{ Form::open(["id" => "formUnblockUnit"]) }}
			<div class="modal-body">

				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">

						<div class="form-group">
							{{ Form::label("course", "Curso") }}
							<span class="help-block text-muted">Escolha um curso.</span>
							{{ Form::select("course", [], null, ["class" => "form-control input-lg"]) }}
						</div>
						<div class="form-group">
							{{ Form::label("unit", "Unidade") }}
							<span class="help-block text-muted">Selecione a unidade a ser desbloqueada.</span>
							{{ Form::select("unit", [], null, ["class" => "form-control input-lg"]) }}
						</div>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<div class="form-submit">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-primary"><i class="fa fa-unlock fa-fw"></i> Desbloquear</button>
				</div>
				<div class="form-process text-center" hidden>
					<i class="fa fa-spin fa-spinner fa-lg text-muted"></i> Processando.
				</div>
			</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
