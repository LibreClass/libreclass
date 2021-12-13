<div class="modal fade" id="modal-remove-period" tabindex="-1" role="Modal Remove Period" aria-labelledby="modalRmvClass" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-blue"><b><i class="fa fa-fw fa-bookmark"></i>{{ ucfirst(strtolower(session('period.singular'))) }}</b></h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <p>Tem certeza que deseja remover {{ strtolower(session('period.article')) }} {{ strtolower(session('period.singular')) }} denominad{{ strtolower(session('period.article')) }} "<b class="periodName"></b>"?</p>
                            <p>Ao remover um{{ strtolower(session('period.article')) == 'a' ? 'a':'' }} {{ strtolower(session('period.singular')) }} você estará removendo também todas as disciplinas e turmas relacionadas.</p>
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
                    <span class="text-center">{{ ucfirst(session('period.article')) }} {{ strtolower(session('period.singular')) }} "<b class="periodName"></b>" foi removid{{ strtolower(session('period.article')) }} com sucesso!</span>
                </div>
            </div>

            <div class="modal-footer">
                <lc-button type="button" variant="secondary" data-dismiss="modal"> Fechar </lc-button>
            </div>
        </div>

    </div>
</div>

<script type="application/javascript">
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