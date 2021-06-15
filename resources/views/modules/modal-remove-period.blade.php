<div class="modal fade" id="modal-remove-period" tabindex="-1" role="Modal Remove Period" aria-labelledby="modalRmvClass" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-blue"><b><i class="fa fa-fw fa-bookmark"></i>Período</b></h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <p>Tem certeza que deseja remover o período denominado "<b class="periodName"></b>"?</p>
                            <p>Ao remover um período você estará removendo também todas as disciplinas e turmas associadas ao mesmo.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger remove" data-id="">
                    <i class="fa fa-trash"></i> Sim, realizar remoção!
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-remove-success" tabindex="-1" role="dialog Modal Success" data-backdrop="true" aria-labelledby="modalRemoveSuccess" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header modal-header-success">
                <i class="fa fa-check fa-lg"></i>
                <span><b>&nbsp;Sucesso</b></span>
                <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <span class="text-center">O período "<b class="periodName"></b>" foi removido com sucesso!</span>
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
        var modal = $("#modal-remove-period");
        var modalSuccess = $("#modal-remove-success");

        modal.on("click", "button.remove", function(e) {
            $.post("/periods/remove", {
                periodId: $(e.target).attr("data-id")
            }, function(data) {
                modal.modal("hide");
                modalSuccess.modal();
                modalSuccess.on("hidden.bs.modal", function() {
                    window.location.href = "/periods";
                });
            });
        });
    });
</script>