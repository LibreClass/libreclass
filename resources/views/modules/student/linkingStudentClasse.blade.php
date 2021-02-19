  <div class="modal fade" id="modalLinkStudentClasse" tabindex="-1" role="Modal Link Student" aria-labelledby="modalLinkStudentClasse" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title text-blue"><b><i class="icon-classes"></i> Vincular aluno em turma</b></h4>
        </div>
        {{ Form::open(["id" => "formLinkStudentClasse"]) }}
        <div class="modal-body">

            {{ Form::hidden("student", encrypt($profile->id)) }}
            <div class="form-group">
              {{ Form::label("classe", "Turma", ["control" => "control-label"]) }}
              <span class="help-block">Selecione uma turma</span>
              {{ Form::select("classe", $listidsclasses, null, ["class" => "form-control"]) }}
            </div>

            <div class="list-offers" id="list-offers"></div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Salvar</button>
        </div>
        </form>
      </div>
    </div>
  </div>
