  <div class="modal fade" id="modalPeriods" tabindex="-1" data-backdrop="static" role="Modal New Periods" aria-labelledby="modalPeriodsLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalPeriodsLabel"><b>Série</b></h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
{{ Form::open(["url" => url("/course/periods"), "id" => "add-periods", "class" => "registerForm"]) }}
            {{ Form::label("course", "Curso") }}
            {{ Form::select("course", $listcourses, null, ["class" => "form-control input-lg", "placeholder" => "Escolha um curso"]) }}
{{ Form::close() }}
          </div>
            <div id="list-periods" class="list-unstyled">
            </div>

<!--            <div class="col-xs-4">
                <input type="text" name="" class="form-control input-border-none">
            </div>
            <div class="col-xs-2">
              <i class="fa fa-trash text-muted"></i>
            </div>-->
           <form class="row input-edit-period">
            <div class="col-xs-4">
                <input type="text" id="input-new-period" name="period" class="form-control input-border-none" placeholder="Nova Serie">
            </div>
            <div class="col-xs-2">
              <button class="btn btn-primary btn-sm">Salvar</button>
            </div>
          </form>

          <hr>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default reload-page" data-dismiss="modal">Sair</button>
        </div>
      </div>
    </div>
  </div>