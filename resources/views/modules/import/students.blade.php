@extends('social.master')

@section('css')
@parent
  <link media="all" type="text/css" rel="stylesheet" href="/css/blocks.css">
@stop

@section('js')
@parent
<script src="/js/blocks.js"></script>
<script src="/js/import.js"></script>
@stop

@section('body')
@parent

<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">

    <div id="block" class="block">
      <div class="row">
        <div class="col-md-12 col-xs-12">
          <h4 ><b>Passo 2/2 - Importação de Alunos</b></h4>
        </div>
        <br>
        <div class="col-md-12 col-xs-12">
          <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 70%;">
              <span>70%</span>
            </div>
          </div>
          <div class="text-center spinupload visible-none">
            <span>Esse processo pode ser demorado. Aguarde alguns minutos.</span>
            <i class="text-info fa fa-2x fa-spinner fa-spin"></i>
          </div>

        </div>

      </div>
      <div id="block-add" class="block">
        <div class="row">
          <div class="col-md-12">
            <div class="text-right">
              <a href="/import" class="btn btn-danger">Cancelar</a>
              <a href="/import/confirmattends" class="btn btn-primary btn-upload">Confirmar</a>
            </div>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Matrícula</th>
                  <th>Nome</th>
                  <th>Turma</th>
                </tr>
              </thead>
              <tbody>
            @foreach( $students  as $student )
              <tr>
                <td>{{ $student[0] }}</td>
                <td>{{ $student[1] }} {{ $student[4] == 0 ? "<span class='label label-primary'>Novo</span>" : "" }}</td>
                <td>{{ $student[5] }}</td>
              </tr>
            @endforeach
              </tbody>
            </table>
            <div class="pull-right">
              <a href="/import" class="btn btn-danger">Cancelar</a>
              <a href="/import/confirmattends" class="btn btn-primary btn-upload">Confirmar</a>
            </div>

            <div class="text-center spinupload visible-none">
              <i class="text-info fa fa-2x fa-spinner fa-spin"></i>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
</div>




@stop
