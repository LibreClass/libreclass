{{ Form::open(["id" => "form-change-status", "url" => ""]) }}
  {{ Form::hidden("key", null, ["class" => "form-control"]) }}
  {{ Form::hidden("status", "D", ["class" => "form-control"]) }}
{{ Form::close() }}
