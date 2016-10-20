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

<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">
    <div class="block">
      <div class="row">
        <div class="col-md-12 col-xs-12">
          <p class=" text-md pull-left"><i class="fa fa-bar-chart-o"></i> RESULTADO FINAL</p>
          <a href='{{ URL::to("lectures") }}' class="pull-right"> Voltar</a>
        </div>

      </div>
    </div>

    <div id="block" class="block">

          <div class="row">
            <div class="col-md-12">
              <p class=""><b>Curso:</b> {{ $offer->getDiscipline()->getPeriod()->getCourse()->name . " - "}}
                <b>Período: </b> {{ $offer->getDiscipline()->getPeriod()->name  }}</p>
              <p class=""><b>Disciplina:</b> {{ $offer->getDiscipline()->name }}</p>
              <p class=""><b>Turma:</b>{{ $offer->getClass()->name ." - " }}
                <b>Sala: </b> {{ $offer->getClass()->classroom }}</p>
              <p class=""><b>Professor: {{ $user->name }}</b></p>
            </div>
            
            <br>
          </div>
          <div class="row">
            <div class="block-list-item">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Aluno</th>

        @foreach($units as $unit)
                        <th>{{ "U". $unit->value }}</th>
        @endforeach
                        <th>Média</th>
                        <th>Rec</th>
                        <th>Faltas</th>
                        <th>Resultado</th>
                      </tr>                
                    </thead>

                    <tbody>
        @foreach( $students as $student )
          <tr>
          <td>{{ $student->name }}</td>
          @foreach ( $student->averages as $average )
            @if( $average < $course->average )
              <td><span class="text-danger">{{ sprintf("%.2f", $average) }}</span></td>
            @else
              <td><span class="">{{ sprintf("%.2f", $average) }}</span></td>
            @endif
          @endforeach
          @if ( $student->med < $course->average )
            <td><b><span class="text-danger">{{ sprintf("%.2f", $student->med) }}</span></b></td>
          @else
            <td><b><span class="text-success">{{ sprintf("%.2f", $student->med) }}</span></b></td>
          @endif  

          @if ( $student->rec < $course->averageFinal )
            <td><b><span class="text-danger">{{ $student->rec }}</span></b></td>
          @else
            <td><b><span class="text-success">{{ $student->rec }}</span></b></td>
          @endif

          @if ( $student->absence/$qtdLessons*100 > $course->absentPercent )
            <td><span class="badge badge-danger">{{ sprintf("%.1f", $student->absence/$qtdLessons*100)." %"}}</span></td>
          @else
            <td><span class="badge">{{ sprintf("%.1f", $student->absence/$qtdLessons*100)." %"}}</span></td>
          @endif

          <td><b><span class="label {{$student->status}}">{{ $student->result }}</span></b></td>
          </tr>
        @endforeach

                    </tbody>
                  </table>
                </div>
              </div>
            </div>  
          </div>

    </div>

            
        
  </div>
</div>

@stop

