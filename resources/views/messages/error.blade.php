@if(session("error"))
  <div id="modalError" class="modal fade" tabindex="-1" role="dialog Modal Error" aria-labelledby="myErrorModal" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header modal-header-error">
          <i class="fa fa-close"></i>
          <span><b> &nbsp;Erro</b></span>
          <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <span class="text-center">{!! session("error") !!}</span>
          </div>
          <br>
          <div class="text-right">
            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="application/javascript">
    $("#modalError").modal();
  </script>
@endif
