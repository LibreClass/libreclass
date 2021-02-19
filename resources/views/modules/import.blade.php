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
          <h3 class="text-blue"><i class="fa fa-upload"></i><b> Importação de dados</b></h3>
          @if(session("message"))
            <div class="alert alert-info alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              {{ session("message") }}
            </div>
          @endif
        </div>

      </div>
    </div>
    <div id="block" class="block">
      <div class="row">
        <div class="col-md-4">
          <img src="/images/sge_logo.jpg" class="center-block img-responsive img-3x" title="Logomarca SGE" alt="SGE"/>
          <br>
          <p class="text-justify text-10">
            Passo 1 - Carregue o arquivo escola-servidorClassePorClasse.csv no formulário de importação de cursos, disciplinas, turmas
            e professores e clique em <b class="label label-primary">Enviar</b>.
          </p>
          <p class="text-justify text-10">
            Passo 2 - Finalizado o passo 1, carregue o arquivo escola-alunosClasse.csv no formulário de importação de alunos. Clique em <b class="label label-primary">Enviar</b> para importar
            os alunos e vinculá-los nas turmas.
          </p>

        </div>
        <div class="col-md-8">
          <div class="panel panel-default">
            <div class="panel-body">
              <h4>Importar cursos, disciplinas, turmas e professores</h4><br>
              {{ Form::open(["url" => URL::to("/import/classwithteacher"), "enctype" => "multipart/form-data"]) }}
                <div class="form-group">
                  {{ Form::file("csv", ["class" => "form-control"])}}
                </div>
                {{ Form::submit("Enviar", ["class" => "btn btn-primary btn-upload"]) }}
              {{ Form::close()}}
            </div>
          </div>

          <div class="panel panel-default">
            <div class="panel-body">
              <h4>Importar alunos</h4><br>
              {{ Form::open(["enctype" => "multipart/form-data"]) }}
                <div class="form-group">
                  {{ Form::file("csv", ["class" => "form-control"])}}
                </div>
                {{ Form::submit("Enviar", ["class" => "btn btn-primary btn-upload"]) }}
              {{ Form::close()}}
            </div>
          </div>
          <div class="text-center spinupload visible-none">
            <i class="text-info fa fa-2x fa-spinner fa-spin"></i>
          </div>
        </div>

      </div>
    </div>
    @if( isset($result) )
    <div id="block-add" class="block">
      <div class="row">
        <div class="col-md-12">
          <div class="alert alert-info"> {{ count($result) }} linhas. </div>
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Matrícula</th>
                  <th>Nome</th>
                  <th>Sexo</th>
                  <th>Data de Nascimento</tH>
                  <th>Status</th>
                </tr>
              </thead>

              <tbody>
                @foreach( $result as $line )
                <tr>
                  <td>{{ $line[1] }}</td>
                  <td>{{ $line[3] }}</td>
                  <td>{{ $line[4] }}</td>
                  <td>{{ $line[5] }}</td>
                  <td>{{ $line["status"] }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
        </div>
      </div>

    </div>
    @endif
  </div>
</div>




@stop
