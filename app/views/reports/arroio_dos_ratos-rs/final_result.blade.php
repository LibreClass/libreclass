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
				<tr>
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
				<tr>
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

		<div>
			<h4 class='text-center breadcrumb'>Observações</h4>
		</div>

		<div>
			<table class='table table-bordered'>
				<tr>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
					<td class='text-center small'><b>1ª tri.</b></td>
					<td class='text-center small'><b>2ª tri.</b></td>
					<td class='text-center small'><b>3ª tri.</b></td>
				</tr>
				<tr>
					<td class='text-center small'>01</td>
					<td class='small'>Parabéns continue sendo um ótimo aluno.</td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
				</tr>
				<tr>
					<td class='text-center small'>02</td>
					<td class='small'>Estamos observando seu esforço para melhorar cada vez mais.</td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
				</tr>
				<tr>
					<td class='text-center small'>03</td>
					<td class='small'>Precisa estudar mais, somente assim conseguirá atingir os objetivos propostos. Acreditamos em ti.</td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
				</tr>
				<tr>
					<td class='text-center small'>04</td>
					<td class='small'>Realizar as tarefas de casa e ter pontualidade na entrega dos trabalhos.</td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
				</tr>
				<tr>
					<td class='text-center small'>05</td>
					<td class='small'>Buscar esclarecimento e dúvidas com o professor.</td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
				</tr>
				<tr>
					<td class='text-center small'>06</td>
					<td class='small'>Cuidar a frequência escolar.</td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
				</tr>
				<tr>
					<td class='text-center small'>07</td>
					<td class='small'>Evitar atrasos no primeiro período e/ou retorno do recreio.</td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
				</tr>
				<tr>
					<td class='text-center small'>08</td>
					<td class='small'>Procure ser mais caprichoso e organizado com seu material escolar, pois facilitará seus estudos.</td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
				</tr>
				<tr>
					<td class='text-center small'>09</td>
					<td class='small'>Evite conversas desnecessárias e brincadeiras em horário de aula, pois estas atitudes estão prejudicando seu rendimento e dos colegas.</td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
				</tr>
				<tr>
					<td class='text-center small'>10</td>
					<td class='small'>Conforme a lei Nº 12.884/2008 é proibido o uso de aparelhos eletrônicos durante as aulas.</td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
				</tr>
				<tr>
					<td class='text-center small'>11</td>
					<td class='small'>Procure ler mais, frequentar a biblioteca.</td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
				</tr>
				<tr>
					<td class='text-center small'>12</td>
					<td class='small'>Conhece, respeita e cumpre as combinações feitas na turma.</td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
				</tr>
			</table>
		</div>

		<br>

		<div class='container-fluid'>
			<div class='row'>
				<div class='col-xs-4 text-center'>
					Data: _____/_____/_____
				</div>
				<div class='col-xs-8 text-center'>
					Ass. Supervisora: ______________________________________
				</div>
			</div>
		</div>

	</body>

</html>
