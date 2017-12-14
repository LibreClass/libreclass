@extends('social.master')

@section('css')
@parent
  {{ HTML::style('css/blocks.css') }}
  {{-- HTML::style('css/import.css') --}}
@stop

@section('js')
@parent
{{ HTML::script('js/blocks.js') }}
{{ HTML::script('js/import.js') }}
@stop

@section('body')
@parent

<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">

    <div id="block" class="block">
      <div class="row">
        <div class="col-md-12 col-xs-12">
          <h4 ><b>Passo 2/3 - Importação de Professores</b></h4>
        </div>
        <br>
        <div class="col-md-12">
          <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%;">
              <span>50%</span>
            </div>
          </div>
          <div class="text-center spinupload visible-none">
            <span>Esse processo pode ser demorado. Aguarde alguns minutos.</span>
            <i class="text-info fa fa-2x fa-spinner fa-spin"></i>
          </div>

        </div>
        <br>
      </div>
      <div id="block-add" class="block">
        <div class="row">
          <div class="col-md-12">
            <div class="pull-right">
              <a href="/import" class="btn btn-danger">Cancelar</a>
              <a href="/import/offer" class="btn btn-primary btn-upload">Confirmar</a>
            </div>
            <br>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Matrícula</th>
                  <th>Professor</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
            @foreach( $teachers as $matricula => $teacher)
              <tr>
                <td>{{ $matricula }}</td>
                <td>{{ $teacher[0]}}</td>
                <td>{{ $teacher[1] == 0 ? "<span class='label label-primary'>Novo</span>" : "" }}</td>
              </tr>
            @endforeach
              </tbody>
            </table>

            <div class="pull-right">
              <a href="/import" class="btn btn-danger">Cancelar</a>
              <a href="/import/offer" class="btn btn-primary btn-upload">Confirmar</a>
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
