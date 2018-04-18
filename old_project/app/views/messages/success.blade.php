@if(Session::has("success"))
  <div id="modalSuccess" class="modal fade" tabindex="-1" role="dialog Modal Success" data-backdrop="true" aria-labelledby="myModalSuccess" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header modal-header-success">
          <i class="fa fa-check fa-lg"></i>
          <span><b> &nbsp;Sucesso</b></span>
          <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <span class="text-center">{{ Session::get("success") }}</span>
          </div>
          <br>
          <div class="text-right">
            <button class="btn btn-default" data-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    $("#modalSuccess").modal();
  </script>
@endif
