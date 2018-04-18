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
          <h4 ><b>Passo 1/3 - Importação da Matriz Curricular</b></h4>
        </div>
        <br>
        <div class="col-md-12 col-xs-12">
          <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%;">
              <span>30%</span>
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
            <div class="pull-right">
              <a href="/import" class="btn btn-danger">Cancelar</a>
              <a href="/import/teacher" class="btn btn-primary btn-upload">Confirmar</a>
            </div>
            <br>
            <br>
            <table class="table">
              <tbody>
                @foreach ( $school as $course => $periods  )
                <tr>
                  <td>
                    <ul class="list-unstyled">
                      <li><h4>{{ $course }}</h4>
                        <ul>
                        @foreach ( $periods as $period => $disciplines )
                          <li>{{ $period }}
                            <ul>
                            @foreach ( $disciplines as $discipline => $status )
                            <li><span class="small">{{$discipline . "  "}}</span>
                              {{ $status == 0 ? "<span class='label label-primary'>Novo</span>" : "" }}
                            </li>
                            @endforeach
                            </ul>
                          </li>
                          <br>
                        @endforeach
                        </ul>
                      </li>
                      <br>
                    </ul>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>

            <div class="pull-right">
              <a href="/import" class="btn btn-danger">Cancelar</a>
              <a href="/import/teacher" class="btn btn-primary btn-upload">Confirmar</a>
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
