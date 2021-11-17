<div class="modal fade" id="modalAddClass" tabindex="-1" role="Modal Add Class" aria-labelledby="modalAddClass" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-blue" id="modalAddClass"><b><i class="icon-classes"></i> Turma</b></h4>
            </div>

            {{ Form::open([ "url" => URL::to("classes/new"), "id" => "formAddClass"]) }}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            {{ Form::label("period", "Curso/" . ucfirst(strtolower(session('period.singular'))) ) }}
                            <span class="help-block text-muted">Selecione o curso e {{ strtolower(session('period.singular')) }} para a turma</span>
                            {{ Form::select("period", $listPeriod, null, ["class" => "form-control"]) }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            {{ Form::label("school_year", "Ano escolar", ["class" => "control-label"]) }}
                            <span class="help-block text-muted">Selecione o ano escolar</span>
                            {{ Form::selectRange('school_year', date('Y'), 2018, date('Y'), ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <span class="text-danger">*</span>
                            {{ Form::label("class", ucfirst(strtolower(session('period.singular'))), ["class" => "control-label"]) }}
                            <span class="help-block text-muted">Ex: 2018.1, 1º Semestre, ... </span>
                            {{ Form::text("class", null, ["class" => "form-control", "autofocus", "required"]) }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-6">
                        <div class="form-group">
                            <span class="text-danger">*</span>
                            {{ Form::label("class", "Turma", ["class" => "control-label"]) }}
                            <span class="help-block text-muted">Informe um nome para identificar a turma.</span>
                            {{ Form::text("name", null, ["class" => "form-control", "autofocus", "required"]) }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div id="block-offer-discipline"></div>
                        <br>
                    </div>
                </div>

                <p class="text-muted">
                    Confira todos os dados antes de salvar.
                </p>
                <p class="text-muted">
                    <span class="text-danger">*</span> Estes campos são obrigatórios.
                </p>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnAddClass"><i class="fa fa-save"></i> Salvar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditClass" tabindex="-1" role="Modal Edit Class" aria-labelledby="modalEditClass" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-blue"><b><i class="icon-classes"></i> Editar Turma</b></h3>
            </div>

            {{ Form::open([ "url" => URL::to("classes/edit"), "id" => "formEditClass"]) }}
            <div class="modal-body">
                {{ Form::hidden("classId", null) }}
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-6">
                        <div class="form-group">
                            {{ Form::label("class", "Nome da turma", ["class" => "control-label"]) }}
                            <span class="help-block text-muted">Informe um nome para identificar a turma. Ex: 2015.1.</span>
                            {{ Form::text("class", null, ["class" => "form-control"]) }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="EditClass-list-disciplines">

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnAddClass"><i class="fa fa-save"></i> Salvar</button>
            </div>
            </form>
        </div>
    </div>
</div>