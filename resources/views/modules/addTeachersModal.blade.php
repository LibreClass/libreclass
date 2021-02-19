  <div class="modal fade" id="modalAddTeacher" tabindex="-1" role="Modal Add Teacher" aria-labelledby="modalAddTeacher" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title text-blue" id="modalAddTeacher"><b><i class="icon-teacher"></i> Professor</b></h3>
        </div>
        {{ Form::open(["id" => "formAddTeacher"]) }}
        <div class="modal-body">

            {{ Form::hidden("teacher", null) }}
            {{ Form::hidden("registered", null) }}

            <div class="form-group">
              {{ Form::label("email", "Email", ["class" => "control-label"]) }}
              <span class="help-block">Informe o email do professor. <img class="spinner" height="25" src="/images/spinner.svg" alt="spinner"></span>
              {{ Form::email("email", null, ["class" => "form-control", "autofocus", "required"]) }}
              <span class="teacher-message text-info"><b>Este professor já está cadastrado no LibreClass e será vinculado à sua instituição.</b></span>
            </div>

            <div class="form-group">
              {{ Form::label("enrollment", "Matrícula", ["class" => "control-label"]) }}
              <span class="help-block">Informe a matrícula do professor.</span>
              {{ Form::text("enrollment", null, ["class" => "form-control", "autofocus", "required"]) }}
            </div>

            <div class="form-group">
              {{ Form::label("name", "Nome", ["class" => "control-label"]) }}
              <span class="help-block">Informe o nome completo do professor.</span>
              {{ Form::text("name", null, ["class" => "form-control", "autofocus", "required"]) }}
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
                                            "Doutor"], null, ["class" => "form-control", "required"]) }}
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Salvar</button>
        </div>
        </form>
      </div>
    </div>
  </div>
