{{ Form::open(["id" => "formAddTeacherDiscipline"]) }}
<div class="modal-body">

    {{ Form::hidden("teacher", null) }}
    <div class="form-group">
        {{ Form::label("search", "Procurar", ["class" => "control-label"]) }}
        <span class="help-block text-muted">Faça a busca informando parte do nome da disciplina.</span>
        <div class="input-group col-md-12">
            {{ Form::text("search", request()->get("search"), ["class" => "form-control"] ) }}
            <span class="input-group-btn">
                <button class="btn btn-primary"><i class="fa fa-lg fa-search"></i></button>
            </span>
        </div>
    </div>

    @foreach($courses as $course)
    @foreach($course->periods as $period)
    @foreach($period->disciplines as $discipline)
    <div class="checkbox">
        <label>
            <input name="discipline" type="checkbox" value='{{ encrypt($discipline->id) }}' {{ \App\Bind::where("user_id", $teacher)->where("discipline_id", $discipline->id)->first() ? "checked" : "" }} />
            <span>{{ $course->name }}</span> /
            <span>{{ $period->name }}</span> /
            <span>{{ $discipline->name }}</span>

        </label>
    </div>
    @endforeach
    @endforeach
    @endforeach

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Concluir</button>
</div>

{{ Form::close() }}