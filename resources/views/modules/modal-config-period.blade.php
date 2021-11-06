<div class="modal fade" id="modal-config-period" tabindex="-1" role="Modal Config Period" aria-labelledby="modalConfigPeriod" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title text-blue"><b><i class="fa fa-fw fa-bookmark"></i>Configurar {{ strtolower(session('period.singular')) }}</b></h3>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <p class="lead">
                        <small>A nomenclatura de um período do curso, definida por padrão por <mark>período</mark>, pode mudar em cada instituição, sendo às vezes chamado de "unidade" ou "ano". Por isso, utilize essa sessão para personalizar a nomenclatura.</small>
                    </p>
                </div>
            </div>

            <form>
                <div class="row form-group">
                    <div class="col-sm-6">
                        <label for="periodSingular" class="col-form-label">Nomenclatura <mark>no singular</mark></label>
                        <input
                            type="text"
                            id="periodSingular"
                            class="form-control"
                            value="{{ strtolower(session('period.singular')) }}">
                    </div>

                    <div class="col-sm-6">
                        <label for="periodPlural" class="col-form-label">Nomenclatura <mark>no plural</mark></label>
                        <input
                            type="text"
                            id="periodPlural"
                            class="form-control"
                            value="{{ strtolower(session('period.plural')) }}">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-6">
                        <label for="periodArticle" class="col-form-label"><mark>Artigo</mark> utilizado para referenciar nomenclatura</label>
                        <input
                            type="text"
                            id="periodArticle"
                            class="form-control"
                            value="{{ strtolower(session('period.article')) }}">
                    </div>
                </div>
            </form>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary save"><i class="fa fa-save"></i> Salvar</button>
        </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-config-success" tabindex="-1" role="dialog Modal Success" data-backdrop="true" aria-labelledby="modalConfigSuccess" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header modal-header-success">
                <i class="fa fa-check fa-lg"></i>
                <span><b>&nbsp;Sucesso</b></span>
                <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <span class="text-center">Nova nomenclatura configurada com sucesso!</span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {

        var modal = $('#modal-config-period');
        var modalSuccess = $('#modal-config-success');

        modal.on('click', 'button.save', function(e) {
            Axios.post('/user/config', {
                periodSingular: modal.find('#periodSingular').val(),
                periodPlural: modal.find('#periodPlural').val(),
                periodArticle: modal.find('#periodArticle').val()
            }).then(function(res) {
                if (res.data.status) {
                    modal.modal('hide');
                    modalSuccess.modal();
                    modalSuccess.on('hidden.bs.modal', function() {
                        window.location.href = '/periods';
                    });
                }
            });
        });
    });
</script>
