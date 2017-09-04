<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="{{ public_path('/css/bootstrap.min.css') }}">
		<style type="text/css" media="screen">
			.vertical {
				vertical-align: middle !important;
				height: 120px;
			}
			.vertical-align {
				vertical-align: middle !important;
			}
			.nowrap {
				white-space: nowrap;
			}
			.limited-width {
				max-width: 3em;
			}
			.rotate {
			  text-align: center;
				white-space: nowrap;
				vertical-align: middle !important;
				min-width: 1.5em;
			}
			.rotate div {
				-moz-transform: rotate(-90.0deg);  /* FF3.5+ */
				-o-transform: rotate(-90.0deg);  /* Opera 10.5 */
				-webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
				margin-left: -10em;
				margin-right: -10em;
			}
			tr, .panel {
				page-break-inside: avoid;
			}
		</style>
	</head>

	<body>

		<header style="margin-bottom: 15px;">
			<table>
				<tr>
					<td style="width: 10%; padding-right: 15px;"><img style="width: 85%;" src="{{ public_path() }}/images/logo-arroio_dos_ratos-rs.jpg" class="img-responsive"></td>
					<td style="width: 80%;" class="text-center">
						<div style="width: 100%;">
							<h4>{{ $data['institution']->name }}</h4>
							<h5>{{ $data['institution']->street }}, {{ $data['institution']->local }}</h5>
							<h5>Código UEE: {{ $data['institution']->uee }}</h5>
						</div>
					</td>
					@if(!empty($data['institution']->photo) && file_exists(public_path($data['institution']->photo)))
						<td style="width: 10%; padding-left: 15px;">
								<img src="{{ public_path() . $data['institution']->photo }}" class="img-responsive">
						</td>
					@endif
				</tr>
			</table>
		</header>

		<div>
			<h5 class='text-center breadcrumb'>Informações do curso</h5>
		</div>

		<div>
			<div class='container small'>
				<div class="row">
					<div class="col-xs-4"><p><b>Tipo de ensino:</b> {{ $data['course']->type }}</p></div>
					<div class="col-xs-4"><p><b>Modalidade:</b> {{ $data['course']->modality }}</p></div>
					<div class="col-xs-4"><p><b>Submodalidade:</b> {{ $data['course']->name }}</p></div>
				</div>
				<div class="row">
					<div class="col-xs-4"><p><b>Turma:</b> {{ $data['classe']->name }}</p></div>
					<div class="col-xs-4"><p><b>Série:</b> {{ $data['period']->name }}</p></div>
					<div class="col-xs-4"><p><b>Período Letivo:</b> {{ $data['classe']->class }}</p></div>
				</div>
			</div>
		</div>

		<div>
			<h5 class='text-center breadcrumb'>Diário de Classe</h5>
		</div>

		<div class="small">
			<table class="table table-bordered table-condensed">
				<tr>
					<th class="text-center vertical"><b>N#</b></th>
					<th class="text-center vertical"><b>Aluno(a)</b></th>
					@foreach ($data['lessons'] as $lesson)
						<th class="rotate vertical"><div>{{ $lesson }}</div></th>
					@endforeach
					<th class="rotate vertical"><div>Faltas</div></th>
				</tr>
				@foreach ($data['students'] as $student)
					<tr>
						<td class="text-center">{{ $student->number }}</td>
						<td class="nowrap">{{ trim($student->name) }}</td>
						@foreach ($student->absences as $absence)
							<td class="text-center">{{ $absence }}</td>
						@endforeach
						<td class="text-center">{{ $student->countAbsences }}</td>
					</tr>
				@endforeach
			</table>
		</div>

		<div>
			<h5 class='text-center breadcrumb'>Notas de aula</h5>
		</div>

		@foreach ($data['lessons_notes'] as $lessons_note)
			<div class="panel panel-default small">
			  <div class="panel-heading"><b>{{ $lessons_note['description'] }}</b></div>
			  <div class="panel-body">
			    <p><b>Título:</b> {{ $lessons_note['title'] }}</p>
			    <p><b>Nota de aula:</b> {{ $lessons_note['note'] }}</p>
			  </div>
			</div>
		@endforeach

		@if ($data['exams'] != null)
			<div>
				<h5 class='text-center breadcrumb'>Avaliações</h5>
			</div>

			<div class="pdf small">
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
		@endif

	</body>

</html>
