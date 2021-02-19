@extends('master')

@section('title')
@parent
  Home
@stop

@section('body')
@parent

<div class="push2x"></div>
<div class="row">
  <div class="col-md-12">
    <div class="alert alert-info text-center"><h4>{{$msg}}</h4></div>
  </div>

</div>






@stop
