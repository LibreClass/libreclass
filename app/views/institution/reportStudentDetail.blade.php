@foreach($disciplines as $discipline)
<div class="panel panel-default click">
  <div class="panel-heading panel-history">
    <div>{{ $discipline->name }}</div>
  </div>
  <div class="panel-body visible-none">
    @foreach( $discipline->units as $unit )
      <table class="table table-condensed">
        <thead>
          <tr>
            <th colspan="2" class="text-center">Unidade {{ $unit->value }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($unit->exams as $exam)
          <tr {{ $exam->aval=="R" ? "class='text-primary'": ""}}>
            <td>{{ $exam->title }}</td>
            <td class="text-right">
              <span class="text-md {{ ($exam->value and $exam->value->value >= $discipline->course->average) ? "text-success" : "text-danger" }}">
                {{ ($exam->value and strlen($exam->value->value)) ? $exam->value->value : "-" }}
              </span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @endforeach

    <table class="table table-condensed">
      <thead>
        <tr>
          <th colspan="2" class="text-center">Resumo</th>
        </str>
      </thead>
      <tbody>
        <tr>
          <td>Frequência</td>
          <td class="text-md text-right {{ $discipline->absencese >= $discipline->course->absentPercent ? "text-success" : "text-danger" }}">
            {{ $discipline->absencese }} %
          </td>
        </tr>
        <tr>
          <td>Média</td>
          <td class="text-md text-success text-right {{ $discipline->average >= $discipline->course->average ? "text-success" : "text-danger" }}">
            {{ $discipline->average }}
          </td>
        </tr>
        @if($discipline->final)
          <tr>
            <td>Recuperação Final</td>
            <td class="text-md text-success text-right {{ $discipline->final->value >= $discipline->course->averageFinal ? "text-success" : "text-danger" }}">
              {{ $discipline->final->value }}
            </td>
          </tr>
        @endif
        <tr>
          <td>Situação</td>
          <td class="text-md {{ $discipline->aproved == "Aprovado" ? 'text-success' : 'text-danger' }} text-right">
            {{ $discipline->aproved }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endforeach
