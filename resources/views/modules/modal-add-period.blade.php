<div class="modal fade" id="modal-add-period" tabindex="-1" role="Modal Add Class" aria-labelledby="modalAddClass" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-blue"><b><i class="fa fa-fw fa-bookmark"></i>Período</b></h3>
      </div>

      {{ Form::open([ "url" => URL::to("periods/save"), "id" => "form-add-period"]) }}
      {{ Form::hidden("period_id", null) }}

      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
							{{ Form::label("course_id", "Curso") }}
              <span class="help-block text-muted" >Selecione o curso</span>
              {{ Form::select("course_id", $listCourses, null, ["class" => "form-control"]) }}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
              {{ Form::label("name", "Nome", ["class" => "control-label"]) }}
              <span class="help-block text-muted">Informe o nome do período ou série</span>
              {{ Form::text("name", null, ["class" => "form-control"]) }}
            </div>
          </div>
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
