@extends('social.master')

@section('css')
@parent
<link media="all" type="text/css" rel="stylesheet" href="/css/blocks.css">
@stop

@section('js')
@parent
<script src="/js/blocks.js"></script>
<script src="/js/lessons.js"></script>
@stop

@section('body')
@parent



<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">

    <div class="block">
      <div class="row center-text">
        <div class="col-md-10 col-sm-10">
          <ol class="breadcrumb">
            <li><b>{{ $info[0]->course }}</b></li>
            <li><b>{{ $info[0]->period }}</b></li>
            <li class="active"><b>{{ $info[0]->class }}</b></li>
          </ol>
        </div>
        <div class="col-md-2 col-sm-2text-right">
          <a class="btn btn-block btn-default btn-block-xs" href="{{ URL::to("/classes/offers?t=".encrypt($info[0]->class_id)) }}">Voltar</a>
        </div>
      </div>
    </div>

    @if(count($students) == 0)
    <div id="block" class="block">
      <tr class="center-text">
        <td>Não existem alunos matriculados nessa turma</td>
      </tr>
    </div>

    @else
    <div id="block" class="block">
      <div class="row">
        <div class="col-md-12">
          <div class="offer-students">
            <table class="table table-hover">
              <tr>
                <th>Nome</th>
                <th></th>
              </tr>
              @foreach($students as $student )
              <tr id='{{ encrypt($student->id) }}' class='list-student'>
                <td>{{ $student->name }}</td>
                <td>
                  <div class="col-xs-10 text-right">
                    @if($student->status == "M")
                    <label class="label label-success"><i class="fa fa-check"></i>
                      Matriculado</label>
                    @elseif($student->status == "D")
                    <label class="label label-danger"><i class="fa fa-thumbs-down"></i>
                      Desistente</label>
                    @elseif($student->status == "T")
                    <label class="label label-info"><i class="fa fa-exchange"></i>
                      Transferido</label>
                    @endif
                  </div>
                  <div class="col-xs-2 text-right">
                    <i class="pull-right fa fa-gears icon-default click" data-toggle="dropdown" aria-expanded="false"></i>
                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                      <li><a class="status-student-offer" data="M"><i class="fa fa-check text-success"></i> Matriculado</a></li>
                      <li><a class="status-student-offer" data="D"><i class="fa fa-thumbs-down text-primary"></i> Desistente</a></li>
                      <li><a class="status-student-offer" data="T"><i class="fa fa-exchange text-primary"></i> Transferido</a></li>
                      <li><a class="status-student-offer" data="R"><i class="fa fa-trash text-danger"></i> Remover</a></li>
                    </ul>
                  </div>
                </td>
              </tr>
              @endforeach

              @endif
            </table>
          </div>
        </div>
      </div>

      <br>
    </div>
  </div>
</div>
</div>
</div>

{{ Form::open(["url" => URL::to("/classes/offers/status-student"), "id" => "statusStudentOffer"]) }}
{{ Form::hidden("offer", $offer) }}
{{ Form::hidden("student", null) }}
{{ Form::hidden("status", null) }}
</form>


@stop