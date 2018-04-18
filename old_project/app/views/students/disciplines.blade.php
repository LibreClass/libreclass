@extends("students.master")

@section("content")
@parent



<div class="panel panel-default">
  <div class="panel-body">

    <h2>Disciplinas</h2>
    <br>

    <ul class="list-unstyled">
      @foreach($offers as $offer)
        <li class="panel panel-default">
          <div class="panel-body">
            <div class="media">
              <div class="media-body">
                <h4 class="media-heading"><a href="/attends/units/{{ Crypt::encrypt($offer->id) }}">{{ $offer->discipline }}</a></h4>
                <?php $teachers = DB::select("SELECT Users.id, Users.name, Users.photo FROM Lectures, Users WHERE Lectures.idOffer=? and Lectures.idUser=Users.id", [$offer->id]); ?>
                @foreach($teachers as $teacher)
                  <img src="{{ strlen($teacher->photo) ? $teacher->photo : "/images/user-photo-default.jpg" }}" class="icon-user-min img-circle">
                  <span class="text-10">{{ $teacher->name }}</span>
                @endforeach
              </div>
            </div>
          </div>
        </li>
      @endforeach
    </ul>
  </div>
</div>
@stop