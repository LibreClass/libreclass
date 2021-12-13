@if(session("success"))
<div id="modalSuccess" class="modal fade" tabindex="-1" role="dialog Modal Success" data-backdrop="true" aria-labelledby="myModalSuccess" aria-hidden="true">
    <div class="modal-dialog modal-sm">

        <div class="modal-content">

            <div class="modal-header modal-header-success">
                <i class="fa fa-check fa-lg"></i>
                <span><b> Sucesso</b></span>
                <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="text-center">
                    <span class="text-center">{!! session("success") !!}</span>
                </div>
                <br>
                <div class="text-right">
                    <lc-button type="button" variant="secondary" data-dismiss="modal"> Fechar </lc-button>
                </div>
            </div>

        </div>

    </div>
</div>

<script type="application/javascript">
    $(function() {
        $("#modalSuccess").modal();
    });
</script>

@endif