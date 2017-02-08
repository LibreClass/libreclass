<div class="modal fade" id="modalCopyLessonFor" tabindex="-1" role="Modal Copy Lesson For" aria-labelledby="modalCopyLessonFor" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title text-blue"><b><i class="fa fa-exchange"></i> Duplicar aula para outro diário</b></h3>
        </div>
        
        {{ Form::open(["id" => "formCopyLessonFor"]) }}
        <div class="modal-body">
          <p>Esta funcionalidade permite que você duplique uma aula para outro diário ativo.</p>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              {{Form::hidden("lesson", null)}}
              <div class="form-group">
                {{ Form::label("offer", "Diário") }}
                <span class="help-block text-muted">Selecione o diário de destino da aula.</span>
                {{ Form::select("offer", [], null, ["class" => "form-control input-lg"]) }}
              </div>
            </div>
          </div>
        </div>
        
        <div class="modal-footer">
          <div class="form-submit">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-exchange"></i> Duplicar</button>
          </div>
          <div class="form-process text-center" hidden>
            <i class="fa fa-spin fa-spinner fa-lg text-muted"></i> Processando.
          </div>
        </div>
        {{ Form::close() }} 
      </div>
    </div>
  </div>  
