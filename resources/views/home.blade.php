@extends('master')

@section('body')
@parent

<div class="cortina"></div>
<div class="home">

	@include('menu')
	@include('featured')

</div>
@include('midia')
@include('features')
@include('plans')
@include('footer')

@stop
