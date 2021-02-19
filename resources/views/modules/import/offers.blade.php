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
          <h4 ><b>Passo 3/3 - Importação da Oferta de Disciplina</b></h4>
        </div>
        <br>


        <div class="col-md-12 col-xs-12">
          <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 70%;">
              <span>70%</span>
            </div>
          </div>
          <div class="text-center spinupload visible-none">
            <span>Esse passo é o mais demorado. Por favor não interrompa. Aguarde.</span>
            <i class="text-info fa fa-2x fa-spinner fa-spin"></i>
          </div>

        </div>

      </div>
      <div id="block-add" class="block">
        <div class="row">
          <div class="col-md-12">
            <div class="pull-right">
              <a href="/import" class="btn btn-danger">Cancelar</a>
              <a href="/import/confirmoffer" class="btn btn-primary btn-upload">Confirmar</a>
            </div>
            <br>
            <br>
            @foreach ( $structure as $class )
              <table class="table">
                <tbody>
                  <tr class="bg-success">
                    <td colspan="3"><b>{{ $class[0][0] }}</b></td>
                  </tr>
                  <tr>
                    <td colspan="2">{{ $class[0][2] }}</td>
                    <td colspan="1">{{ $class[0][1] }}</td>
                  </tr>
                  @foreach ( $class[1] as $offer )
                    @foreach ( $offer[8] as $disc )
                      <tr>
                        <td>{{ $disc }}</td>
                        <td>{{ $offer[0] }} - {{ $offer[1] }}</td>
                        <td>{{ $offer[6] }}</td>
                      </tr>
                    @endforeach
                  @endforeach
                </tbody>
              </table>
            @endforeach
            <div class="pull-right">
              <a href="/import" class="btn btn-danger">Cancelar</a>
              <a href="/import/confirmoffer" class="btn btn-primary btn-upload">Confirmar</a>
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
