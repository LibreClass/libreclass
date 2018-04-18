<div class="row">
  <div class="col-sm-8">
    <h4>Histórico</h4>
  </div>
  <div class="col-sm-4">
    <div class="form-inline text-right">
      Período Letivo:
      {{ Form::select("class", ['2017' => '2017', '2018' => '2018'], null, ["class" => "form-control", "id" => "class-change"]) }}
    </div>
  </div>
</div>
<br>
<div id="class-list"></div>
