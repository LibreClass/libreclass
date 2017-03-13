  <div class="modal fade" id="modalAddTeacher" tabindex="-1" role="Modal Add Teacher" aria-labelledby="modalAddTeacher" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title text-blue" id="modalAddTeacher"><b><i class="icon-teacher"></i> Professor</b></h3>
        </div>
        {{ Form::open(["id" => "formAddTeacher"]) }}
        <div class="modal-body">

            {{ Form::hidden("teacher", null) }}

            <div class="form-group">
              {{ Form::label("email", "Email", ["class" => "control-label"]) }}
              <span class="help-block">Informe o email do professor.</span>
              {{ Form::email("email", null, ["class" => "form-control input-lg", "autofocus", "required"]) }}
            </div>

            <div class="form-group">
              {{ Form::label("enrollment", "Matrícula", ["class" => "control-label"]) }}
              <span class="help-block">Informe a matrícula do professor.</span>
              {{ Form::text("enrollment", null, ["class" => "form-control input-lg", "autofocus", "required"]) }}
            </div>

            <div class="form-group">
              {{ Form::label("name", "Nome", ["class" => "control-label"]) }}
              <span class="help-block">Informe o nome completo do professor.</span>
              {{ Form::text("name", null, ["class" => "form-control input-lg", "autofocus", "required"]) }}
            </div>

            <div class="form-group">
              {{ Form::label("formation", "Formação Acadêmica", ["control" => "control-label"]) }}
              <span class="help-block">Informe a formação acadêmica principal do professor.</span>
              {{ Form::select("formation", ["Não quero informar",
                                            "Ensino Fundamental",
                                            "Ensino Médio",
                                            "Ensino Superior Incompleto",
                                            "Ensino Superior Completo",
                                            "Pós-Graduado",
                                            "Mestre",
                                            "Doutor"], null, ["class" => "form-control input-lg"]) }}
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Salvar</button>
        </div>
        {{ Form::close() }}
      </div>
    </div>
  </div>
