@extends('social.master')
@section('css')
@parent
{{ HTML::style('css/blocks.css') }}
@stop

@section('js')
@parent
{{ HTML::script('js/blocks.js') }}
@stop

@section('body')
@parent

<?php $list = []; ?>
<div class="block">
  <div class="row">
    <div class="col-xs-10">
      <h3><i class="fa fa-user"></i> Frequência</h3>
    </div>
    <div class="col-xs-2 text-right">
      <a href="{{ URL::to("lectures") }}" class="btn btn-default">Voltar</a>
    </div>
  </div>
</div>

<div class="block">
  <div class="table-responsive">
    <table class="table table-hover table-bordered">
      <thead>
        <tr style="height: 100px;">
          <td style="width: 40%" class="text-center"><h4><b>Unidades</b></h4></td>
          @foreach( $units as $unit )
            <?php $aux = $unit->getLessons(); $list = array_merge($list, $aux->toArray()); ?>
          <td colspan="{{ count($aux) }}" style="padding: 3px; vertical-align: middle;">
            <div class="text-transform-x90" style="width: 70px; height: 20px;"><b>Unidade {{ $unit->value }}</b></div>
          </td>
          @endforeach
        </tr>
        <tr>
          <td><h4><b>Alunos/Aula</b></h4></td>
          @for ( $i = 1 ; $i <= count($list) ; $i++ )
            <td>{{ $i }}</td>
          @endfor
        </tr>
      </thead>
      <tbody>
      @foreach( $students as $student )
        <tr>
          <td>{{ $student->name }}</td>
          @foreach ( $list as $lesson )
          <td style="width: 25px;"><span>{{ Frequency::getValue($student->id, $lesson["id"]) == "P" ? "." : Frequency::getValue($student->id, $lesson["id"]) }}</span></td>
          @endforeach
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>  
</div>
@stop


