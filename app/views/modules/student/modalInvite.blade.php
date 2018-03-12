  <div class="modal fade" id="modalInvite" tabindex="-1" role="Modal Info Invite" aria-labelledby="modalInvite" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><b><i class="fa fa-envelope-o fa-fw"></i> Convidar</b></h4>
        </div>
        {{ Form::open(["id" => "formInvite", "url" => URL::to("/user/invite")]) }}
        <div class="modal-body">

          {{ Form::hidden("guest", Crypt::encrypt($profile->id)) }}
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group">
                {{ Form::label("email", "Email", ["class" => "control-label"]) }}
                <span class="help-block">Para convidar informe um email válido.</span>
                {{ Form::text("email", null, ["class" => "form-control", "required"]) }}
              </div>
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o fa-fw"></i> Convidar</button>
        </div>
        {{ Form::close() }}
      </div>
    </div>
  </div>
