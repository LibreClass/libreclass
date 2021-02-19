<div class="modal fade" id="modalReport" tabindex="-1" role="Modal Send Report" aria-labelledby="modalReportLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-blue" id="modalReportLabel"><b><i class="fa fa-support"></i> Contate o suporte</b></h3>
			</div>
			<form action="/suggestion" method="post">
				@csrf
				<div class="modal-body">
					<p class="text-center">
						Sua opinião é importante para o crescimento do LibreClass.
					</p>
					<p class="text-justify">
						Utilize o formulário abaixo para informar um erro, sugerir melhorias
						ou propor novas funcionalidades. A mensagem será enviada para
						<strong>{{ env('MAIL_SUPORTE') }}</strong> e nossa equipe avaliará sua
						solicitação para atendê-lo o mais breve possível.
					</p>
					<div class="form-group">
						<label class="control-label">Título</label>
						<span class="help-block text-muted">Informe um título para sua mensagem.</span>
						<input type="text" name="title" class="form-control">
					</div>
					<div class="form-group">
						<label class="control-label">Tipo da Mensagem</label>
						<span class="help-block text-muted">Escolha um tipo para a mensagem.</span>
						<select name="value" class="form-control">
							<option value="S">Dar sugestão</option>
							<option value="B">Relatar erros</option>
							<option value="O">Outro</option>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">Descrição</label>
						<span class="help-block text-muted">Descreva abaixo a sua solicitação</span>
						<textarea name="description" class="form-control" cols="6"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-primary"><i class="fa fa-send"></i> Enviar</button>
				</div>
			</form>
		</div>
	</div>
</div>
