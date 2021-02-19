<div class="modal fade" id="modalForgot" tabindex="-1" role="dialog" aria-labelledby="modalForgotLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4>Informe o seu email para recuperar o seu acesso ao LibreClass</h4>
			</div>
			<div class="modal-body">
				<form action="/forgot-password" method="post">
					@csrf
					<div class='row'>
						<div class="col-md-12">
							<div class="form-group">
								<input type="email" name="email" placeholder="Digite seu email" class="form-control" required autofocus>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary btn-block-xs">Enviar</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>