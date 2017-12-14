@extends("students.master")

@section('js')
@parent
{{ HTML::script('js/students/units.js') }}
@stop


@section("content")
@parent

<div class="panel panel-default">

  <div class="panel-heading">
    <h4>{{ $discipline->name }}</h4>
    @foreach($teachers as $teacher)
      <img src="{{ strlen($teacher->photo) ? $teacher->photo : "/images/user-photo-default.jpg" }}" class="icon-user-min img-circle">
      <span >{{ $teacher->name }}</span>
    @endforeach
    <i id="average" average="{{ $course->average }}" averageFinal="{{ $course->averageFinal }}" hidden></i>
  </div>

  <div class="panel-body">

    <ul class="nav nav-tabs units">
      <?php $first = true; ?>
      @foreach($units as $unit)
        <li role="presentation" data="{{ Crypt::encrypt($unit->id) }}">
          <a href="#">Unidade {{ $unit->value }}</a></li>
      @endforeach
    </ul>
    <br>


    <div class="feed-unit">
      <ul class="list-unstyled" id="list-units">
        <li class="panel panel-notification model">
          <div class="panel-body">
            <div class="notification-header text-blue">
              <span class="notification-date"><i class="fa fa-calendar fa-fw"></i></span>
              <span class="notification-info label label-success pull-right"></span>
            </div>
            <div>
              <span class="notification-title"></span>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>
@stop
