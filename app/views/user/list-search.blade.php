
@foreach($users as $user)
  @if(($user->type == "P" or $user->type == "A") and $i++ < 5)
    <li data="{{ Crypt::encrypt($user->id)}}" class="click list-group-item">
      <div class="pull-left mr">
        {{ HTML::image($user->photo, null, ["class" => "user-photo-2x img-circle"]) }}  
      </div>
      <div >
        <span>{{ $user->name }}</span> - 
        <span>{{ $user->email }}</span><br>
        <span>{{ date("d/m/Y", strtotime($user->birthdate)) }}</span>  
      </div>
    </li>
  @endif
@endforeach
