<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</head>

	<body>

		<header style="margin-bottom: 30px;">
			<table>
				<tr>
					<td style="width: 20%; padding-right: 15px;"><img style="width: 85%;" src="{{ public_path() }}/images/logo-arroio_dos_ratos-rs.jpg" class="img-responsive"></td>
					<td style="width: 60%;" class="text-center">
						<div style="width: 100%;">
							<h4>{{ $institution->name }}</h4>
							<h5>{{ $institution->street }}, {{ $institution->local }}</h5>
							<h5>Código UEE: {{ $institution->uee }}</h5>
						</div>
					</td>
					<td style="width: 20%; padding-left: 15px;"><img src="{{ public_path() . $institution->photo }}" class="img-responsive"></td>
				</tr>
			</table>
		</header>

		<div>
			<ol class="breadcrumb bg-white">
        <li>{{ $unit->offer->discipline->period->course->name }}</li>
        <li>{{ $unit->offer->discipline->period->name }}</li>
        <li>{{ $unit->offer->getClass()->fullName() }}</li>
        <li>Unidade {{ $unit->value }}</li>
      </ol>
		</div>

		<div class="pdf">
		  @foreach ($data['exams'] as $exam)
				<h4><strong>Título da avaliação: </strong>{{ $exam['data']['title'] }}
					<small>{{ date_format(date_create($exam['data']['date']), "d/m/Y") }}</small>
				</h4>
				@if ($exam['data']['comments'])
					<h5><strong>Descrição: </strong>{{ $exam['data']['comments'] }}</h5>
				@endif
				<table class="table table-bordered">
					@foreach ($exam['descriptions'] as $d)
						<tr>
							<td>
								<h5><b>{{ $d->student->name }} <small>{{ $d->approved == 'A' ? 'Aprovado' : 'Reprovado' }}</small></b></h5>
								<p>{{ $d->description }}</p>
							</td>
						</tr>
					@endforeach
				</table>
			@endforeach
		</div>

	</body>

</html>
