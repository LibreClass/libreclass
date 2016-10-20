
  <div class="modal fade" id="modalReport" tabindex="-1" role="Modal Send Report" aria-labelledby="modalReportLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title text-blue" id="modalReportLabel"><b><i class="fa fa-support"></i> Contate o suporte</b></h3>
        </div>
        {{ Form::open(["url" => url("/suggestion")]) }}
        <div class="modal-body">
          <p class="text-center">Sua opinião é importante para o crescimento do LibreClass.</p>
          <p>Utilize o formulário abaixo para informar um erro, sugerir melhorias ou propor novas funcionalidades. Nossa equipe receberá e avaliará sua solicitação para atendê-lo o mais breve possível.</p>
          <div class="form-group">
            {{ Form::label("title", "Título", ["class" => "control-label"]) }}
            <span class="help-block text-muted">Informe um título para sua mensagem.</span>
            {{ Form::text("title", null, ["class" => "form-control"]) }}
          </div>
          <div class="form-group">
            {{ Form::label("value", "Tipo da Mensagem", ["class" => "control-label"] ) }}
            <span class="help-block text-muted">Escolha um tipo para a mensagem.</span>
            {{ Form::select("value", ["B" => "Relatar Bug", "S" => "Dar Sugestão", "O" => "Outro"], null, ["class" => "form-control"]) }}
          </div>
          <div class="form-group">
            {{ Form::label("description", "Descrição", ["class" => "control-label"]) }}
            <span class="help-block text-muted">Descreva abaixo a sua solicitação</span>
            {{ Form::textarea("description", "", ["class" => "form-control", "size" => "30x6"]) }}
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-send"></i> Enviar</button>
        </div>
        {{ Form::close() }} 
      </div>
    </div>
  </div>  
