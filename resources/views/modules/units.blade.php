@extends('social.master')

@section('css')
@parent
  <link media="all" type="text/css" rel="stylesheet" href="/css/blocks.css">
@stop

@section('js')
@parent
  <script src="/js/blocks.js"></script>
  <script src="/js/lessons.js"></script>
@stop

@section('body')
@parent



<div class="row">
  <div class="col-md-8 col-xs-12 col-sm-12">

    <div id="block" class="block">
      <div class="row">
        <div class="col-md-12 col-xs-12">
          <h4 ></h4>
        </div>
      </div>
      <div class="block-list">
        <div class="block-list-item">
          {{ Form::open(["url" => URL::to("/lectures/units/addstudent")]) }}
            <div class="row">
              <div class="col-md-12 col-sm-12">
                  {{ Form::hidden("unit", request()->get("u")) }}
                  {{ Form::label("student", "Adicionar Aluno") }}
              </div>
              <div class="col-md-10 col-sm-10">
                  <div class="form-group">
                    {{ Form::select("student", $list_students, null, ["class" => "form-control"]) }}
                  </div>
              </div>
              <div class="col-md-2 col-sm-2">
                <div class="form-group">
                  {{ Form::submit("Adicionar", ["class" => "form-control"]) }}
                </div>
              </div>
            </div>
          </form>
            <div class="row">
              <div class="col-md-12">
                <div class="offer-students">
                  <table class="table table-hover">
                    <tr>
                      <th>Nome</th>
                      <th></th>
                    </tr>
                    @foreach($students as $student )
                    <tr id='{{ encrypt($student->id) }}' class='list-student'>
                      <td>{{ $student->name }}</td>
                      <td>
                        <i class="fa fa-remove pull-right text-danger remove-student"></i>
                      </td>
                    </tr>
                    @endforeach
                  </table>
                </div>
              </div>
            </div>

            <br>
        </div>
      </div>
    </div>
  </div>
</div>

{{ Form::open(["url" => URL::to("/lectures/units/rmstudent"), "id" => "rmstudent"]) }}
  {{ Form::hidden("unit", request()->get("u")) }}
  {{ Form::hidden("student", null) }}
</form>


@stop

