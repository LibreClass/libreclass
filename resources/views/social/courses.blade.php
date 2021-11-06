@extends('social.master')

@section('css')
@parent
	<link rel="stylesheet" type="text/css" href="/css/blocks.css">
@stop

@section('js')
@parent
	<script type="text/javascript" src="/js/blocks.js"></script>
	<script type="text/javascript" src="/js/course.js"></script>
	<script type="text/javascript" src="/js/validations/socialCourses.js"></script>
@stop

@section('body')
@parent
	<div class="row">
		<div class="col-md-8 col-xs-12 col-sm-12">
			<div id="block" class="block">
			@if (session("message"))
				<div class="row">
					<div class="col-md-12 col-xs-12 col-sm-12">
						<div class="alert alert-info">{{ session("message") }}</div>
					</div>
				</div>
			@endif
				<div class="row">
					<div class="col-md-6 col-xs-12">
						<h3 class="text-blue"><i class="fa fa-folder-o"></i> <b>Cursos</b></h3>
					</div>
					@if( auth()->user()->type == "I" )
						<div class="col-md-6 col-xs-12">
							<div class="list-inline text-right">

								<lc-button
									id="new-course"
								>
									Novo Curso
								</lc-button>

								@if(!count($courses) == 0)
									<lc-button
										id="new-periods"
										variant="secondary"
									>
										Novo Ano
									</lc-button>
								@endif
							</div>

						</div>
					@endif
				</div>
			</div>

			@if(count($courses) == 0)
			<div class="block">
				<div class="text-center">Você não possui cursos cadastrados.</div>
			</div>

			<div class="block">
				<div >
					<h3>Como posso obter diários?</h3>
					<br>
					<p>Para obter diários é necessário que uma instituição de ensino vincule a sua conta a uma disciplina.
						 Quando isto acontecer, a disciplina liberada pela instituição irá aparecer aqui e você poderá ter acesso
						 à mesma.</p>
				</div>
			</div>

			@else
			<div class="block">
				<!--inicio da listagem de cursos -->
				@forelse( $courses as $course )
				<div class="panel panel-default data" key="{{ encrypt($course->id) }}" >
					<div class="panel-heading">
						<div class="row">
							<div class="col-md-10 col-xs-10">
								<span><b><a class="course-edit click" key="{{ encrypt($course->id) }}">{{ $course->modality ." ".$course->name }}</a></b></span>
							</div>
							<div class="col-md-2 col-xs-2 text-right">
								<i class="fa fa-gears icon-default click" data-toggle="dropdown" aria-expanded="false"></i>
								<ul class="dropdown-menu" role="menu">
									<li><a class="course-edit click" key="{{ encrypt($course->id) }}"><i class="fa fa-edit text-info"></i> Editar</a></li>
									<li><a class="trash click"><i class="fa fa-trash text-danger"></i> Deletar</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-6">
								<ul class="list-unstyled">
									<li><b>Tipo de Ensino: </b>{{ $course->type }}</li>
									<li><b>Modalidade: </b>{{ $course->modality }}</li>
									<li><b>Trimestres/Unidades: </b>{{ $course->quant_unit }}</li>
									<li><b>Faltas (Reprovação): </b>{{ $course->absent_percent . "%" }}</li>
									<li><b>Média do Curso: </b>{{ $course->average }}</li>
									<li><b>Média da Final: </b>{{ $course->average_final }}</li>
									<li><b>Perfil Curricular: </b>
										@if($course->curricularProfile != "")
											<a href="{{"/uploads/curricularProfile/".$course->curricularProfile }}" target="_blank">Abrir arquivo</a>
										@else
											<span>Perfil não anexado.</span>
										@endif
									</li>
								</ul>
							</div>
							<div class="col-md-6">
								<h5><b>Anos</b></h5>
								<ul class="list-inline">
								@forelse($course->periods as $period)
									<li><span class="label label-default">{{ $period->name }}</span></li>
								@empty
									<li class="text-light">
										<a class="click period-zero">Cadastrar um{{ strtolower(session('period.article')) }} {{ strtolower(session('period.singular')) }}</a>
									</li>
								@endforelse
								</ul>
							</div>
						</div>
					</div>
				</div>

				@empty
				<!--fim lista de cursos-->
				@endif

			</div> 
				@endforelse 
			</div> 
		</div>
	</div>

	@include("social.course-new")
	@include("social.periods-new", ["listcourses" => $listcourses])

@stop
