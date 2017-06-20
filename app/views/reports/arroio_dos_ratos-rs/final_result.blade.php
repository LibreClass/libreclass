<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<style type="text/css" media="screen">
			.page-break-inside {
				page-break-inside: avoid;
			}
			.page-break {
				page-break-before: always;
			}
			.box {
				padding: 15px;
				border: 1px solid #ddd;
				margin-bottom: 15px
			}

			.bg-muted {
				padding: 5px;
				background: #eee;
			}


		</style>
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
			<h4 class='text-center breadcrumb'>Boletim de Avaliação - Resultado Final</h4>
		</div>

		<div>
			<table class='table table-bordered'>
				<tr>
					<td colspan="3">Aluno: {{ $data['student']['name'] }}</td>
				</tr>
				<tr>
					<td>Curso: {{ $data['course']['name'] }}</td>
					<td>Classe: {{ $data['classe']['name'] }}</td>
					<td>Turma: {{ $data['classe']['class'] }}</td>
				</tr>
			</table>
		</div>

		<div>
			<h4 class='text-center breadcrumb'>Aproveitamento</h4>
		</div>

		<div>
			<table class='table table-bordered'>
				<tr class="bg-muted">
					<td class="text-center small" colspan="2" style='vertical-align: middle;'>Componente curricular</td>
					@foreach ($data['disciplines'] as $discipline)
					<td class="text-center small" colspan="2" style='vertical-align: middle;'>{{ $discipline['name'] }}</td>
					@endforeach
				</tr>
				<tr>
					<td colspan="2"></td>
					@foreach ($data['disciplines'] as $discipline)
						<td class='text-center small'>N</td>
						<td class='text-center small'>R</td>
					@endforeach
				</tr>
				<tr>
					<td class="small" colspan="2">1º Trimestre</td>
					@foreach ($data['disciplines'] as $discipline)
						<td class='text-center small'>{{ isset($discipline[1]['average']) ? $discipline[1]['average'] : 0  }}</td>
						<td class='text-center small'>{{ isset($discipline[1]['recovery']) ? $discipline[1]['recovery'] : '--'  }}</td>
					@endforeach
				</tr>
				<tr>
					<td class="small" colspan="2">2º Trimestre</td>
					@foreach ($data['disciplines'] as $discipline)
						<td class='text-center small'>{{ isset($discipline[2]['average']) ? $discipline[2]['average'] : 0  }}</td>
						<td class='text-center small'>{{ isset($discipline[2]['recovery']) ? $discipline[2]['recovery'] : '--'  }}</td>
					@endforeach
				</tr>
				<tr>
					<td class="small" colspan="2">3º Trimestre</td>
					@foreach ($data['disciplines'] as $discipline)
						<td class='text-center small'>{{ isset($discipline[3]['average']) ? $discipline[3]['average'] : 0  }}</td>
						<td class='text-center small'>{{ isset($discipline[3]['recovery']) ? $discipline[3]['recovery'] : '--'  }}</td>
					@endforeach
				</tr>
				<tr class='bg-muted'>
					<td class="small" colspan="2">Assiduidade</td>
					@foreach ($data['disciplines'] as $discipline)
						<td class='text-center small'>A</td>
						<td class='text-center small'>F</td>
					@endforeach
				</tr>
				<tr>
					<td class="small" colspan="2">1º Trimestre</td>
					@foreach ($data['disciplines'] as $discipline)
						<td class='text-center small'>{{ isset($discipline[1]['lessons']) ? $discipline[1]['lessons'] : 0  }}</td>
						<td class='text-center small'>{{ isset($discipline[1]['absenceses']) ? $discipline[1]['absenceses'] : 0  }}</td>
					@endforeach
				</tr>
				<tr>
					<td class="small" colspan="2">2º Trimestre</td>
					@foreach ($data['disciplines'] as $discipline)
						<td class='text-center small'>{{ isset($discipline[2]['lessons']) ? $discipline[2]['lessons'] : 0  }}</td>
						<td class='text-center small'>{{ isset($discipline[2]['absenceses']) ? $discipline[2]['absenceses'] : 0  }}</td>
					@endforeach
				</tr>
				<tr>
					<td class="small" colspan="2">3º Trimestre</td>
					@foreach ($data['disciplines'] as $discipline)
						<td class='text-center small'>{{ isset($discipline[3]['lessons']) ? $discipline[3]['lessons'] : 0  }}</td>
						<td class='text-center small'>{{ isset($discipline[3]['absenceses']) ? $discipline[3]['absenceses'] : 0  }}</td>
					@endforeach
				</tr>
			</table>
		</div>

		<div>
			<p class='small'>N = Nota média.<br>R = Nota de recuperação.<br>A = Aulas realizadas.<br>F = Faltas.</p>
			<p class='small'>Obs: A nota mínima para que o aluno seja considerado aprovado, ou seja, apto às aprendizagens seguintes é 50.</p>
		</div>

		<br><br><br>

		<div class='container-fluid'>
			<div class='row'>
				<div class='col-xs-4 text-center'>
					Data: _____/_____/_____
				</div>
				<div class='col-xs-8 text-center'>
					Ass. Supervisor(a): ___________________________________
				</div>
			</div>
		</div>

		<div class="page-break small">
			<h4 class="text-center breadcrumb">Pareceres descritivos</h4>

				@foreach ($data['pareceres']->disciplines as $discipline)
					{{-- {{ var_dump($discipline); }} --}}

					@if($discipline->hasParecer)
						<div>
							<h4>{{$discipline->name}}</h4>
						</div>

						@foreach ($discipline->units as $unit)
							@if(isset($unit->pareceres))
								<div class="bg-muted">
									<strong>{{ $unit->value }}º TRIMESTRE</strong> <small>(Unidade {{ $unit->value }} )</small>
								</div>
								<br />
								@forelse($unit['pareceres'] as $parecer)
									<div class="box">
										<div class="row">
											<div class="col-xs-6 small text-uppercase">
												<strong>Tipo de avaliação:</strong> <br />{{ $parecer->exam->type }}
											</div>
											<div class="col-xs-6 small text-uppercase">
												<strong>Título: </strong> <br />{{ $parecer->exam->title }}
											</div>
										</div>
										<br />
										<div class="small text-uppercase"><strong>Parecer: {{ $parecer->approved == 'A' ? 'Aprovado' : 'Reprovado' }} </strong></div>
										<p>{{ $parecer->description }}</p>
									</div>
								@empty
									<p>
										Parecer não informado
									</p>
								@endforelse
							@endif
						@endforeach
						<br />
					@endif
				@endforeach
			</div>
		</div>


	</body>

</html>
