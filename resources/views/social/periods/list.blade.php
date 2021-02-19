@if($periods)
<table class="table table-hover table-striped">
  <thead>
    <tr>
      <th>Período</th>
      {{-- <th>Sequência/Progressão</th> --}}
      <th></th>
    </tr>
  </thead>
  <tbody>
    @foreach( $periods as $period )
      <tr data-id="{{ encrypt($period->id) }}">
        <td>{{ $period->name }}</td>
        {{-- <td>{{ $period->progression_value }}</td> --}}
        <td>
          <div class="col-md-12">
            <i class="pull-right fa fa-gears icon-default click" data-toggle="dropdown" aria-expanded="false"></i>
            <ul class="dropdown-menu dropdown-menu-right" role="menu">
              <li><a class="open-modal-add-period" edit data-id="{{$period->id}}"><i class="fa fa-edit text-info"></i> Editar</a></li>
              {{-- <li><a class="trash"><i class="fa fa-trash text-danger"></i> Deletar</a></li> --}}
            </ul>
          </div>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
@else
<h4 class="text-center">O curso selecionado não possui períodos</h4>
@endif
