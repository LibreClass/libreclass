@extends('social.master')

@section('css')
@parent
  <link media="all" type="text/css" rel="stylesheet" href="/css/blocks.css">
  {{-- HTML::style('css/import.css') --}}
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
          <h4 ><b>Passo 1/2 - Importação da Turmas</b></h4>
        </div>
        <br>


        <div class="col-md-12 col-xs-12">
          <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 30%;">
              <span>30%</span>
            </div>
          </div>
          <div class="text-center spinupload visible-none">
            <span>Por favor não interrompa. Aguarde.</span>
            <i class="text-info fa fa-2x fa-spinner fa-spin"></i>
          </div>

        </div>

      </div>
      <div id="block-add" class="block">
        <div class="row">
          <div class="col-md-12">
            <div class="pull-right">
              <a href="/import" class="btn btn-danger">Cancelar</a>
              <a href="/import/confirm-classes" class="btn btn-primary btn-upload">Confirmar</a>
            </div>
            <br>
            <br>
              <table class="table">
                <thead>
                  <tr>
                    <th>Turma</th>
                    <th>{{ ucfirst(strtolower(session('period.singular'))) }}</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ( $classes as $class )
                      <tr>
                        <td>{{ $class->name }}</td>
                        <td>{{ $class->period }}</td>
                        <td><span class='label label-primary'>Novo</span></td>
                      </tr>
                  @endforeach
                </tbody>
              </table>
            <div class="pull-right">
              <a href="/import" class="btn btn-danger">Cancelar</a>
              <a href="/import/confirm-classes" class="btn btn-primary btn-upload">Confirmar</a>
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
