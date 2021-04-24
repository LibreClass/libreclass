<div class="modal fade" id="modalReport" tabindex="-1" role="Modal Send Report" aria-labelledby="modalReportLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-blue" id="modalReportLabel"><b><i class="fa fa-support"></i> Contate o suporte</b></h3>
			</div>
			<form action="/suggestion" method="post" data-toggle="validator">
				@csrf
				<div class="modal-body">
					<p class="text-justify">
						Utilize o formulário abaixo para informar um erro, sugerir melhorias
						ou propor novas funcionalidades.
					</p>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label"><span class="text-danger">* </span>Seu nome</label>
								<input type="text" name="name" class="form-control" required> 
							</div>
						</div>
						<div class="col-md-6 col-xs-6">
							<div class="form-group">
								<label class="control-label"><span class="text-danger">* </span>E-mail para resposta</label>
								<input type="email" name="emailUser" class="form-control" required>
								
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-7s col-xs-8">
							<div class="form-group">
								<label class="control-label"><span class="text-danger">* </span>Assunto</label>
								<input type="text" name="title" class="form-control" required> 
							</div>
						</div>
						<div class="col-md-4 ">
							<div class="form-group">
								<label class="control-label">Tipo da Mensagem</label>
								<select name="value" class="form-control">
									<option value="S">Realizar sugestão</option>
									<option value="B">Relatar erros</option>
									<option value="O">Outro</option>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label"><span class="text-danger">* </span>Descrição detalhada</label>
						<textarea name="description" class="form-control" cols="6" required></textarea>
					</div>
					<div class="form-group">
						<label class="control-label">Texto do erro <span style="font-weight: 400;" class="text-muted"> (opcional)</span></label>
						<textarea name="textError" class="form-control"></textarea>
					</div>
					<div class="form-group">
						<label class="control-label">Link da pagina <span style="font-weight: 400;" class="text-muted"> (opcional)</span></label>
						<input type="text" name="link" class="form-control" placeholder="Cole o link da pagina com problema">
					</div>
					<span style="font-weight: 400;" class="text-muted">Os campos marcados com asterisco (*) são de preenchimento obrigatório</span>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-primary"><i class="fa fa-send"></i> Enviar</button>
				</div>
			</form>
		</div>
	</div>
</div>
