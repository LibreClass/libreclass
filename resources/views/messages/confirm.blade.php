<div id="modalConfirm" class="modal fade" tabindex="-1" role="dialog Modal Confirm" aria-labelledby="myConfirmModal" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class='modal-header'>
                <i class="fa fa-warning fa-lg text-warning"></i>
                <b class="title-confirm"> Você tem certeza? </b>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p id="confirm-text" class="text-center">
                            <!-- Texto incluido dinamicamente com método $.confirm em block.js -->
                        </p>
                        <div class="text-right">
                            <lc-button id="confirm-yes"> <i class="fa fa-lg fa-check text-white"></i> Sim </lc-button>
                            <lc-button variant="secondary" data-dismiss="modal"> <i class="fa fa-lg fa-close text-danger"></i> Cancelar </lc-button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>