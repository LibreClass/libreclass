<div class="modal fade" id="modalEditDiscipline" tabindex="-1" role="Modal Add Class" aria-labelledby="modalAddClass" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-blue"><b><i class="fa fa-fw fa-list-ul"></i> Editar Disciplina</b></h3>
            </div>

            {{ Form::open([ "url" => URL::to("disciplines/edit"), "id" => "formEditDiscipline"]) }}
            {{ Form::hidden("discipline", null) }}

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            {{ Form::label("name", "Nome", ["class" => "control-label"]) }}
                            <span class="help-block text-muted">Escolha um título que identifique a disciplina</span>
                            {{ Form::text("name", null, ["class" => "form-control"]) }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-6">
                        <div class="form-group">
                            {{ Form::label("ementa", "Ementa", ["class" => "control-label"]) }}
                            <span class="help-block text-muted">A ementa descreve os conteúdos abordados pela disciplina.</span>
                            {{ Form::textarea("ementa", null, ["class" => "form-control"]) }}
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <lc-button type="button" variant="secondary" data-dismiss="modal"> Cancelar </lc-button>
                <lc-button type="submit" id="btnEditDiscipline"> Salvar </lc-button>
            </div>

            {{ Form::close() }}
        </div>
    </div>
</div>