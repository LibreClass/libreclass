@extends('social.master')

@section('css')
@parent
<link media="all" type="text/css" rel="stylesheet" href="/css/blocks.css">
<link media="all" type="text/css" rel="stylesheet" href="/css/forms.css">
@stop

@section('js')
@parent
<script src="/js/blocks.js"></script>
<script src="/js/offers.js"></script>
<script src="/js/sheriff.js"></script>

@stop

@section('body')
@parent

<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">

    @section('offer-option')
    @show

  </div>

</div>



@stop
