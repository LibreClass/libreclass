@extends('social.master')

@section('css')
@parent
<link media="all" type="text/css" rel="stylesheet" href="/css/blocks.css">
@stop

@section('js')
@parent
<script src="/js/blocks.js"></script>
<script src="/js/periods.js"></script>
{{-- <script src="/js/validations/socialDisciplines.js"></script> --}}
@stop

@section('body')
@parent

@if(!$listCourses)
<div class="row">
    <div class="col-md-8 col-xs-12 col-sm-12">
        <div class="block">
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <h3 class="text-blue"><i class="fa fa-bookmark"></i> <b>Períodos</b></h3>
                </div>
            </div>
        </div>
        <div class="block">
            <h3 class="text-blue"><b>Você não possui períodos cadastrados.</b></h3>
            <p>Para cadastrar um período é preciso ter pelo menos um curso cadastrado.</p>
            <br>
            <a href="/courses"><i class="fa fa-folder-o"></i> Cadastrar um novo curso</a>
        </div>
    </div>
</div>
@else

@include('messages.success')

<div class="row" id="view-periods">
    <div class="col-md-8 col-xs-12 col-sm-12">
        <div id="block">
            <div class="block">
                <div class="row">
                    <div class="col-sm-6 col-xs-12">
                        <h3 class="text-blue"><i class="fa fa-bookmark"></i> <b>Períodos</b></h3>
                    </div>
                    <div class="col-sm-6 col-xs-12 text-right">
                        <button class="open-modal-add-period btn btn-primary btn-block-xs"><b><i class="fa fa-plus"></i> Novo Ano</b></button>
                    </div>
                </div>
            </div>
            <div class="block">
                <div class="row">
                    <div class="col-md-12">
                        {{ Form::open(["id" => "select-course-period"]) }}
							<div class="form-group">
								{{ Form::label("course_id", "Curso", ["class" => "control-label"]) }}
								<span class="help-block text-muted">Selecione um curso para visualizar os períodos cadastrados.</span>
								{{ Form::select("course_id", $listCourses, $course_id, ["class" => "form-control"]) }}
							</div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="block-list">

                            <div class="row block-list-item">
                                <div class="col-md-12">
                                    <div class="list-periods">
                                        <!-- social.periods.list -->
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@include("modules.modal-add-period", ['listCourses' => $listCourses])
@include("modules.modal-remove-period", ['listCourses' => $listCourses])

@stop