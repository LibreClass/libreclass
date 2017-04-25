  <div class="modal fade" id="modalEditRegisterTeacher" tabindex="-1" role="Modal Add Teacher" aria-labelledby="modalAddTeacher" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title text-blue"><b><i class="icon-teacher"></i> Editar matrícula</b></h3>
        </div>
        {{ Form::open(["id" => "formEditRegisterTeacher", 'url' => '/user/teacher/update-enrollment']) }}
        <div class="modal-body">

            {{ Form::hidden("teacher", null) }}

            <div class="form-group">
              {{ Form::label("enrollment", "Matrícula", ["class" => "control-label"]) }}
              <span class="help-block">Matrícula do professor.</span>
              {{ Form::text("enrollment", null, ["class" => "form-control input-lg", "autofocus", "required"]) }}
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
