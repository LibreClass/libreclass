<div class="modal fade" id="modalCourse" tabindex="-1" role="Modal New Course" aria-labelledby="modalCourseLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalCourseLabel"><b>Curso</b></h3>
			</div>

			<form action="/courses/save" id="form-course" class="registerForm" enctype="multipart/form-data" method="post">
				<div class="modal-body">
					@csrf
					<input type="hidden" name="course">
					<div class="form-group">
						<label class="control-label">Nome do Curso</label>
						<span class="help-block text-muted">Digite o nome do curso.</span>
						<input type="text" name="name" class="form-control" placeholder="Digite aqui o nome do curso">
					</div>
					<div class="form-group">
						<label class="control-label">Tipo de Ensino</label>
						<span class="help-block text-muted">Informe o tipo de ensino. Ex: (Ensino Superior, Ensino Profissional, Ensino Regular)</span>
						<input type="text" name="type" class="form-control" placeholder="Digite aqui o tipo de ensino">
					</div>
					<div class="form-group">
						<label class="control-label">Modalidade</label>
						<span class="help-block text-muted">Informe a modalidade do curso. Ex: (Subsequente, Integrado)</span>
						<input type="text" name="modality" class="form-control" placeholder="Digite aqui a modalidade">
					</div>
					<div class="form-group">
						<label class="control-label">Total de trimestres</label>
						<span class="help-block text-muted">Quantidade de trimestres ou unidades</span>
						<input type="text" name="quant_unit" class="form-control" placeholder="Informe a quantidade de trimestres ou unidades do curso">
					</div>
					<div class="form-group">
						<label class="control-label">Percentual de faltas para reprovação (%)</label>
						<span class="help-block text-muted">Informe um percentual de faltas para reprovação do seu curso.</span>
						<input type="text" name="absent_percent" value="25" class="form-control error">
					</div>
					<div class="form-group">
						<label class="control-label">Média para aprovação</label>
						<span class="help-block text-muted">Informe o valor da média de aprovação do seu curso.</span>
						<input type="text" name="average" value="7.0" class="form-control grade">
					</div>
					<div class="form-group">
						<label class="control-label">Média final</label>
						<span class="help-block text-muted">Informe o valor da média final de aprovação do seu curso.</span>
						<input type="text" name="average_final" value="5.0" class="form-control grade">
					</div>
					<div class="form-group">
						<label class="control-label">Perfil curricular</label>
						<span class="help-block text-muted">Anexe o arquivo do perfil curricular do curso (PDF).</span>
						<input type="file" name="curricularProfile" class="form-control">
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-primary">Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>
