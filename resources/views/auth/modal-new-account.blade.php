<div class="modal fade" id="modalNewAccount" tabindex="-1" role="dialog" aria-labelledby="modalNewAccountLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<div class="modal-title">
					<h4>Faça o seu cadastro para acessar o Libreclass</h4>
				</div>
			</div>
			<div class="modal-body">
				<form action="/">
					<div class="form-group">
						<label>Nome</label>
						<input type="text" name="name" placeholder="Informe o seu nome" class="form-control" required>
					</div>
					<div class="form-group">
						<label class="text-left">Email</label>
						<input type="email" name="email" placeholder="Informe um email válido" class="form-control" required>
					</div>
					<div class="form-group has-feedback">
						<label class="text-left">Senha</label>
						<input type="password" name="password" placeholder="Informe uma senha segura" class="form-control" required>
						<span id="eye-magic" class="glyphicon glyphicon-eye-open form-control-feedback click text-muted" ></span>
					</div>
					<div class="text-center">
						<button class="btn btn-primary btn-block-xs">Cadastrar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>