<div class="modal fade" id="modalReceiveClass" tabindex="-1" role="Modal Add Class" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-blue"><b><i class="fa fa-share fa-fw text-muted"></i> Receber turmas</b></h3>
			</div>
			{{ Form::open([ "url" => URL::to("classes/receive-class"), "id" => "formReceiveClass"]) }}
			<div class="modal-body">
				<p>
					Nesta função é possível copiar as turmas do ano anterior para o ano escolar selecionado.
					As as turmas serão copiadas sem os alunos.
					<br />Selecione abaixo as turmas e informe se deseja copiar com as ofertas atuais.
				</p>
				<br />
				<b>Turmas de {{ $school_year - 1 }}</b>

				<ul class="list-group list-classes">
				</ul>

				<p class="text-muted">
					Confira todos os dados antes de salvar.
				</p>

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Confirmar</button>
			</div>
			</form>
		</div>
	</div>
</div>
