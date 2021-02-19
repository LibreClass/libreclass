@php
	$i = 1;
@endphp

@if(count($disciplines) == 0)
<span class="text-center">Não existem disciplinas na série selecionada</span>
@else
  <span class="help-block text-muted">Selecione abaixo as disciplinas que serão ofertadas para a turma.</span>
  @foreach( $disciplines as $discipline )
    <div class="checkbox">
      <label>
        {{ Form::checkbox( "discipline_" . $i++, encrypt($discipline->id), true) }} &nbsp;
        {{ $discipline->name }}
      </label>
    </div>
  @endforeach

@endif
