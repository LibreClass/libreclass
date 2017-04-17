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
					<td colspan="3">Aluno: Francisco Caio Silva Ladislau</td>
				</tr>
				<tr>
					<td>Série: 3º Ano</td>
					<td>Turma: 667</td>
					<td>Ano: 2017</td>
				</tr>
			</table>
		</div>

		<div>
			<h4 class='text-center breadcrumb'>Aproveitamento</h4>
		</div>

		<div>
			<table class='table table-bordered'>
				<tr>
					<td class="small" colspan="2" style='vertical-align: middle;'>Componente curricular</td>
					@foreach ($data['disciplines'] as $discipline)
					<td class="small" colspan="2" style='vertical-align: middle;'>{{ $discipline->name }}</td>
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
					<td class="small" colspan="2">1ª Unidade</td>
					@foreach ($data['disciplines'] as $discipline)
						<td class='text-center small'>50</td>
						<td class='text-center small'>50</td>
					@endforeach
				</tr>
				<tr>
					<td class="small" colspan="2">2ª Unidade</td>
					@foreach ($data['disciplines'] as $discipline)
						<td class='text-center small'>50</td>
						<td class='text-center small'>50</td>
					@endforeach
				</tr>
				<tr>
					<td class="small" colspan="2">3ª Unidade</td>
					@foreach ($data['disciplines'] as $discipline)
						<td class='text-center small'>50</td>
						<td class='text-center small'>50</td>
					@endforeach
				</tr>
			</table>
		</div>

		<div>
			<p class='small'>Obs: A nota mínima para que o aluno seja considerado aprovado, ou seja, apto às aprendizagens seguintes é 50.</p>
		</div>

		<div>
			<h4 class='text-center breadcrumb'>Assiduidade</h4>
		</div>

		<div>
			<table class='table table-bordered'>
				<tr>
					<td class='text-center'></td>
					<td class='text-center'><b>1ª Unidade</b></td>
					<td class='text-center'><b>2ª Unidade</b></td>
					<td class='text-center'><b>3ª Unidade</b></td>
				</tr>
				<tr>
					<td class='text-center'><b>Dias letivos</b></td>
					<td class='text-center'>30</td>
					<td class='text-center'>30</td>
					<td class='text-center'>30</td>
				</tr>
				<tr>
					<td class='text-center'><b>Faltas</b></td>
					<td class='text-center'>28</td>
					<td class='text-center'>27</td>
					<td class='text-center'>30</td>
				</tr>
			</table>
		</div>

		<div>
			<h4 class='text-center breadcrumb'>Observações</h4>
		</div>

		<div>
			<table class='table table-bordered'>
				<tr>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
					<td class='text-center small'><b>1ª Un.</b></td>
					<td class='text-center small'><b>2ª Un.</b></td>
					<td class='text-center small'><b>3ª Un.</b></td>
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
					<td class='small'>Buscar esclarecimento e dúvidas com o professor.</td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
				</tr>
				<tr>
					<td class='text-center small'>05</td>
					<td class='small'>Cuidar a frequência escolar.</td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
				</tr>
				<tr>
					<td class='text-center small'>06</td>
					<td class='small'>Procure ser mais caprichoso e organizado com seu material escolar, pois facilitará seus estudos.</td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
				</tr>
				<tr>
					<td class='text-center small'>07</td>
					<td class='small'>Evite conversas desnecessárias e brincadeiras em horário de aula, pois estas atitudes estão prejudicando seu rendimento e dos colegas.</td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
				</tr>
				<tr>
					<td class='text-center small'>08</td>
					<td class='small'>Procure ler mais, frequentar a biblioteca.</td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
				</tr>
				<tr>
					<td class='text-center small'>09</td>
					<td class='small'>Conhece, respeita e cumpre as combinações feitas na turma.</td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
				</tr>
				<tr>
					<td class='text-center small'>10</td>
					<td class='small'>Realize suas tarefas em casa.</td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
					<td class='text-center small'></td>
				</tr>
			</table>
		</div>

		<!-- <div>
			<ol class="breadcrumb">
        <li>{{-- $data['unit']->offer->discipline->period->course->name --}}</li>
        <li>{{-- $data['unit']->offer->discipline->period->name --}}</li>
        <li>{{-- $data['unit']->offer->getClass()->fullName() --}}</li>
        <li>Unidade {{-- $data['unit']->value --}}</li>
      </ol>
		</div> -->

		<!-- <div class="pdf"> -->
		  <!-- @ foreach ($ data['exams'] as $ exam) -->
		  	<!-- <h5><strong>Disciplina: </strong> {{-- $data['discipline'] --}}</h5> -->
		  	<!-- <h5> -->
		  		<!-- @ if (count($ data['teachers']) == 1) -->
			  		<!-- <strong>Professor(a): </strong> -->
		  			<!-- <span>{{-- $data['teachers'][0] --}}</span> -->
		  		<!-- @ elseif (count($ data['teachers']) > 1) -->
		  			<!-- <strong>Professores(as): </strong> -->
						<!-- @ foreach ($ data['teachers'] as $ teacher) -->
							<!-- <span>{{-- $teacher --}}</span> -->
						<!-- @ endforeach -->
		  		<!-- @ endif -->
		  	<!-- <h5> -->
				<!-- <h5><strong>Título da avaliação: </strong>{{-- $exam['data']['title'] --}} -->
					<!-- <small>{{-- date_format(date_create($exam['data']['date']), "d/m/Y") --}}</small> -->
				<!-- </h5> -->
				<!-- @ if ($exam['data']['comments']) -->
					<!-- <h5><strong>Descrição: </strong>{{-- $exam['data']['comments'] --}}</h5> -->
				<!-- @ endif -->
				<!-- <table class="table table-bordered"> -->
					<!-- @ foreach ($ exam['descriptions'] as $ d) -->
						<!-- <tr> -->
							<!-- <td> -->
								<h5><b>{{-- $d->student->name --}} <small>{{-- $d->approved == 'A' ? 'Aprovado' : 'Reprovado' --}}</small></b></h5>
								<!-- <h5><b>Aulas realizadas: {{-- $data['unit']->count_lessons --}} / Faltas: {{-- $d->student->absence --}}</b></h5> -->
								<!-- <p>{{-- $d->description --}}</p> -->
							<!-- </td> -->
						<!-- </tr> -->
					<!-- @ endforeach -->
				<!-- </table> -->
			<!-- @ endforeach -->
		<!-- </div> -->

	</body>

</html>
