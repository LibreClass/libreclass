@extends('social.master')

@section('body')
@parent
  <div class="row">
    <div class="col-md-12 text-center">
      <br>
      <br>
      <h3><b>:( Ooops! Error {{ $code }}</b></h3>
      <br>
      <br>
      <p>Não conseguimos encontrar o que você está procurando!</p>
      <p>Se este erro persistir contate o <a href="{{"mailto:suporte@sysvale.com?subject=Error=".$code."-".date("Y-m-d")}}"><b>suporte@sysvale.com</b></a></p>
    </div>
  </div>
@stop

