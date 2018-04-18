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
  <div class="col-md-10 col-xs-12 col-sm-12">
    <div class="block">
      <div class="row">
        <div class="col-sm-10 col-xs-12">
          <h3 class="text-blue"><i class="fa fa fa-bar-chart-o"></i> <b>RESULTADO FINAL</b></h3>
        </div>
        <div class="col-sm-2 col-xs-12">
          <a href='{{ URL::to("lectures") }}' class="btn btn-default btn-block btn-block-xs">Voltar</a>
        </div>

      </div>
    </div>

    <div id="block" class="block">

          <div class="row">
            <div class="col-md-12">
              <p ><b>Curso:</b> {{ $offer->getDiscipline()->getPeriod()->getCourse()->name . " - "}}
                <b>Período: </b> {{ $offer->getDiscipline()->getPeriod()->name  }}</p>
              <p ><b>Disciplina: </b>{{ $offer->getDiscipline()->name }}</p>
              <p ><b>Turma: </b>{{ $offer->getClass()->name ." - " }}
                <b>Sala: </b> {{ $offer->getClass()->classroom }}</p>
              <p ><b>Professor: </b> {{ $user->name }}</p>
            </div>

            <br>
          </div>
          <div class="row">
            <div class="block-list-item">
              <div class="col-xs-12 col-sm-12">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Aluno</th>

        @foreach($units as $unit)
                        <th>{{ "U". $unit->value }}</th>
        @endforeach
                        <th>Média</th>
                        <th>Rec. Final</th>
                        <th>Faltas</th>
                        {{-- <th>Resultado</th> --}}
                      </tr>
                    </thead>

                    <tbody>
											@foreach( $students as $student )
												<tr>
													<td>{{ $student->name }}</td>
													@foreach ( $student->averages as $average )
														@if( $average < $course->average )
															<td><span class="badge badge-danger">{{ sprintf("%.2f", $average) }}</span></td>
														@else
															<td><span class="badge badge-primary">{{ sprintf("%.2f", $average) }}</span></td>
														@endif
													@endforeach
													@if ( $student->med < $course->average )
														<td><span class="text-danger">{{ sprintf("%.2f", $student->med) }}</span></td>
													@else
														<td><span class="text-success">{{ sprintf("%.2f", $student->med) }}</span></td>
													@endif

													@if ( $student->rec < $course->averageFinal )
														@if($student->rec == '-')
															<td><b><span>{{ $student->rec }}</span></b></td>
														@else
															<td><b><span class="badge badge-danger">{{ $student->rec }}</span></b></td>
														@endif
													@else
														<td><b><span class="badge badge-primary">{{ $student->rec }}</span></b></td>
													@endif

													@if ( $student->absence/$qtdLessons*100 > $course->absentPercent )
														<td><span class="badge badge-danger">{{ sprintf("%.1f", $student->absence/$qtdLessons*100)." %"}}</span></td>
													@else
														<td><span class="badge badge-primary">{{ sprintf("%.1f", $student->absence/$qtdLessons*100)." %"}}</span></td>
													@endif

													{{-- <td><b><span class="label {{$student->status}}">{{ $student->result }}</span></b></td> --}}
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
