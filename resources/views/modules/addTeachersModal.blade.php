<div class="modal fade" id="modalAddTeacher" tabindex="-1" role="Modal Add Teacher" aria-labelledby="modalAddTeacher"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-blue" id="modalAddTeacher"><b><i class="icon-teacher"></i> Professor</b>
                    </h3>
            </div>
            {{ Form::open(["id" => "formAddTeacher"]) }}
            <div class="modal-body">
                {{ Form::hidden("teacher", null) }}
                {{ Form::hidden("registered", null) }}
                <div class="form-group">
                    {{ Form::label("email", "*Email", ["class" => "control-label"]) }}
                    <span class="help-block">Informe o email do professor. <img class="spinner" height="25"
                            src="/images/spinner.svg" alt="spinner"></span>
                    {{ Form::email("email", null, ["class" => "form-control", "autofocus", "required"]) }}
                    <span class="teacher-message text-info"><b>Este professor já está cadastrado no LibreClass e será
                            vinculado à sua instituição.</b></span>
                </div>
                <div class="form-group" id="search">
                    {{ Form::label("enrollment", "*Matrícula", ["class" => "control-label"]) }}
                    <span class="help-block">Informe a matrícula do professor.<img class="spinner-enrrollment" height="25"
                            src="/images/spinner.svg" alt="spinner-enrrollment"></span>
                    {{ Form::text("enrollment", null, ["class" => "form-control", "autofocus", "required"]) }}
                    <span class="verify-enrollment text-info"></span>
                </div>

                <div class="form-group">
                    {{ Form::label("name", "*Nome", ["class" => "control-label"]) }}
                    <span class="help-block">Informe o nome completo do professor.</span>
                    {{ Form::text("name", null, ["class" => "form-control", "autofocus", "required"]) }}
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label("gender", "*Sexo") }}
                            {{ Form::select("gender", ['M'=> 'Masculino', 'F'=> 'Feminino'], null, ["class" => "form-control", 'required']) }}
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-6">
                        <div class="form-group">
                            {{ Form::label("date-day", "Data de Nascimento: ")}}
                            <div class="form-inline">
                                {{ Form::selectRange("date-day", 1, 31, null, ["class" => "form-control"]) }}
                                {{ Form::selectRange("date-month", 1, 12, null, ["class" => "form-control"]) }}
                                {{ Form::selectRange("date-year", date("Y"), date("Y")-100, null, ["class" => "form-control"]) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label("formation", "*Formação Acadêmica", ["control" => "control-label"]) }}
                    <span class="help-block">Informe a formação acadêmica principal do professor.</span>
                    {{ Form::select("formation", ["Não quero informar",
                                            "Ensino Fundamental",
                                            "Ensino Médio",
                                            "Ensino Superior Incompleto",
                                            "Ensino Superior Completo",
                                            "Pós-Graduado",
                                            "Mestre",
                                            "Doutor"], null, ["class" => "form-control", "required"]) }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary g"><i class="fa fa-save"></i> Salvar</button>
            </div>
            </form>
        </div>
    </div>
</div>