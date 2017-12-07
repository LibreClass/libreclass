
  <div class="modal fade" id="modalCourse" tabindex="-1" role="Modal New Course" aria-labelledby="modalCourseLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title" id="modalCourseLabel"><b>Curso</b></h3>
        </div>
        {{ Form::open(["url" => url("/courses/save"), "id" => "form-course", "class" => "registerForm", "enctype" => "multipart/form-data"]) }}
        <div class="modal-body">
            {{ Form::hidden('course', null) }}
          <div class="form-group">
            {{ Form::label("name", "Nome do Curso", ["class" => "control-label"]) }}
            <span class="help-block text-muted">Digite o nome do curso.</span>
            {{ Form::text("name", null, ["class" => "form-control input-lg", "placeholder" => "Digite aqui o nome do curso"]) }}
          </div>
          <div class="form-group">
            {{ Form::label("type", "Tipo de Ensino", ["class" => "control-label"]) }}
            <span class="help-block text-muted">Informe o tipo de ensino. Ex: (Ensino Superior, Ensino Profissional, Ensino Regular)</span>
            {{ Form::text("type", null, ["class" => "form-control input-lg", "placeholder" => "Digite aqui o tipo de ensino"]) }}
          </div>
          <div class="form-group">
            {{ Form::label("modality", "Modalidade", ["class" => "control-label"]) }}
            <span class="help-block text-muted">Informe a modalidade do curso. Ex: (Subsequente, Integrado)</span>
            {{ Form::text("modality", null, ["class" => "form-control input-lg", "placeholder" => "Digite aqui a modalidade"]) }}
          </div>
					<div class="form-group">
            {{ Form::label("quant_unit", "Total de trimestres", ["class" => "control-label"]) }}
            <span class="help-block text-muted">Quantidade de trimestres ou unidades</span>
            {{ Form::text("quant_unit", null, ["class" => "form-control grade input-lg", "placeholder" => "Informe a quantidade de trimestres ou unidades do curso"]) }}
          </div>
          <div class="form-group">
            {{ Form::label("absent", "Percentual de Faltas para Reprovação (%)", ["class" => "control-label"] ) }}
            <span class="help-block text-muted">Informe um percentual de faltas para reprovação do seu curso.</span>
            {{ Form::text("absentPercent", "25", ["class" => "form-control error input-lg"]) }}
          </div>
          <div class="form-group">
            {{ Form::label("average", "Média para aprovação", ["class" => "control-label"]) }}
            <span class="help-block text-muted">Informe o valor da média de aprovação do seu curso.</span>
            {{ Form::text("average", "7.0", ["class" => "form-control grade input-lg"]) }}
          </div>
          <div class="form-group">
            {{ Form::label("averageFinal", "Média final", ["class" => "control-label"]) }}
            <span class="help-block text-muted">Informe o valor da média final de aprovação do seu curso.</span>
            {{ Form::text("averageFinal", "5.0", ["class" => "form-control input-lg grade"]) }}
          </div>
          <div class="form-group">
            {{ Form::label("curricularProfile", "Perfil Curricular", ["class" => "control-label"]) }}
            <span class="help-block text-muted">Anexe o arquivo do perfil curricular do curso (PDF).</span>
            {{ Form::file("curricularProfile", ["class" => "form-control input-lg"]) }}
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
        {{ Form::close() }}
      </div>
    </div>
  </div>
