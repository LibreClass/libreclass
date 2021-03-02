<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="{{ public_path('/css/bootstrap.min.css') }}">
		<style type="text/css" media="screen">
			.vertical {
				vertical-align: middle !important;
				height: 4.5rem;
			}
			.vertical-align {
				vertical-align: middle !important;
			}
			td {
				white-space: nowrap;
			}
			tr, .panel {
				page-break-inside: avoid;
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
		</style>
	</head>

	<body>
		@include('reports.header', [ 'institution' => $data['institution'] ])

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
						<td>{{ trim($student->name) }}</td>
						@foreach ($student->absences as $absence)
							<td class="text-center">{{ $absence }}</td>
						@endforeach
						<td class="text-center">{{ $student->countAbsences }}</td>
					</tr>
				@endforeach
			</table>
			<div class="text-right small">
				Legenda da frequência: 'F': Falta, '.': Presença, 'A': Atestado
			</div>
			<br />
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

		<div>
			<h5 class='text-center breadcrumb'>Avaliações</h5>
		</div>

		<div class="small">
			<table class="table table-bordered table-condensed">
				<tr>
					<th class="text-center vertical-align"><b>N#</b></th>
					<th class="text-center vertical-align"><b>Aluno(a)</b></th>
					@foreach ($data['exams'] as $exam)
						<th class="limited-width text-center vertical-align"><div>Avaliação {{ $exam->number }} ({{ $exam->date }})</div></th>
					@endforeach
					<th class="limited-width text-center vertical-align">Média</th>
					<th class="limited-width text-center vertical-align">Avaliação de recuperação</th>
				</tr>
				@foreach ($data['students'] as $student)
					<tr>
						<td class="text-center">{{ $student->number }}</td>
						<td>{{ trim($student->name) }}</td>
						@foreach ($student->exams as $exam)
							<td class="text-center">{{ $exam }}</td>
						@endforeach
						<td class="text-center">{{ $student->average }}</td>
						<td class="text-center">{{ $student->finalAverage }}</td>
					</tr>
				@endforeach
			</table>
		</div>
		@include('analytics')
	</body>

</html>
