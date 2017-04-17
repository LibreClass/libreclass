<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	</head>

	<body>

		<header style="margin-bottom: 15px;">
			<table>
				<tr>
					<td style="width: 16%; padding-right: 15px;"><img style="width: 85%;" src="{{ public_path() }}/images/logo-arroio_dos_ratos-rs.jpg" class="img-responsive"></td>
					<td style="width: 68%;" class="text-center">
						<div style="width: 100%;">
							<h4>{{ $data['institution']->name }}</h4>
							<h5>{{ $data['institution']->street }}, {{ $data['institution']->local }}</h5>
							<h5>Código UEE: {{ $data['institution']->uee }}</h5>
						</div>
					</td>
					<td style="width: 16%; padding-left: 15px;"><img src="{{ public_path() . $data['institution']->photo }}" class="img-responsive"></td>
				</tr>
			</table>
		</header>

		<div>
			<ol class="breadcrumb">
        <li>{{ $data['unit']->offer->discipline->period->course->name }}</li>
        <li>{{ $data['unit']->offer->discipline->period->name }}</li>
        <li>{{ $data['unit']->offer->getClass()->fullName() }}</li>
        <li>Unidade {{ $data['unit']->value }}</li>
      </ol>
		</div>

		<div class="pdf">
		  @foreach ($data['exams'] as $exam)
		  	<h5><strong>Disciplina: </strong> {{ $data['discipline'] }}</h5>
		  	<h5>
		  		@if (count($data['teachers']) == 1)
			  		<strong>Professor(a): </strong>
		  			<span>{{ $data['teachers'][0] }}</span>
		  		@elseif (count($data['teachers']) > 1)
		  			<strong>Professores(as): </strong>
						@foreach ($data['teachers'] as $teacher)
							<span>{{ $teacher }}</span>
						@endforeach
		  		@endif
		  	<h5>
		  	<h5><b>Aulas realizadas:</b> {{ $data['unit']->count_lessons }}</h5>
				<h5><strong>Título da avaliação: </strong>{{ $exam['data']['title'] }}
					<small>{{ date_format(date_create($exam['data']['date']), "d/m/Y") }}</small>
				</h5>
				@if ($exam['data']['comments'])
					<h5><strong>Descrição: </strong>{{ $exam['data']['comments'] }}</h5>
				@endif
				<table class="table table-bordered">
					@foreach ($exam['descriptions'] as $d)
						<tr>
							<td>
								<h5><b>{{ $d->student->name }} <small>{{ $d->approved == 'A' ? 'Aprovado' : 'Reprovado' }}</small></b></h5>
								<h5>Faltas: {{ $d->student->absence }}</b></h5>
								<p>{{ $d->description }}</p>
							</td>
						</tr>
					@endforeach
				</table>
			@endforeach
		</div>

	</body>

</html>
