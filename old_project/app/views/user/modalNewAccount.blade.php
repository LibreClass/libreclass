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

            {{ Form::open(["url" => url("/")]) }}
              <div class="form-group">
                {{ Form::label("name", "Nome", ["class" => ""] ) }}
                {{ Form::text("name", null, [ "placeholder" => "Informe o seu nome", "class" => "form-control", "required" ])}}
              </div>
              <div class="form-group">
                {{ Form::label("email",null,  ["class" => " text-left"]) }}
                {{ Form::email("email", null, [ "placeholder" => "Informe um email válido", "class" => "form-control","required" ])}}
              </div>
              <div class="form-group has-feedback">
                {{ Form::label("password", "Senha", ["class" => " text-left"]) }}
                {{ Form::password("password", [ "placeholder" => "Informe uma senha segura", "class" => "form-control", "required" ])}}
                <span id="eye-magic" class="glyphicon glyphicon-eye-open form-control-feedback click text-muted" ></span>
              </div>
              <div class="text-center">
              <button class="btn btn-primary btn-block-xs">Cadastrar</button>
            {{ Form::close() }}

        </div>
    </div>
  </div>
</div>