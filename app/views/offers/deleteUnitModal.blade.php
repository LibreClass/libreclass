<div class="modal fade" id="modalDeleteUnit" tabindex="-1" role="Modal Delete Unit" aria-labelledby="modalDeleteUnit" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-blue"><b><i class="fa fa-plus text-primary fa-fw"></i> Deletar unidade</b></h3>
			</div>

			{{ Form::open(["id" => "formDeleteUnit", "url" => "/classes/delete-units"]) }}
			<div class="modal-body">

				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">

						<div class="form-group">
							{{ Form::label("course", "Curso") }}
							<span class="help-block text-muted">Selecione o curso que deseja excluir a unidade.</span>
							{{ Form::select("course", [], null, ["class" => "form-control"]) }}
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
					<button type="submit" class="btn btn-primary"><i class="fa fa-trash fa-fw"></i> Deletar</button>
				</div>
				<div class="form-process text-center" hidden>
					<i class="fa fa-spin fa-spinner fa-lg text-muted"></i> Processando.
				</div>
			</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
