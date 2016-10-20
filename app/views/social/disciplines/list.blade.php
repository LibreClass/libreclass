@if($disciplines)
<table class="table table-hover table-striped">
  <thead>
    <tr>
      <th><span class="text-md">Período</span></th>
      <th><span class="text-md">Disciplina</span></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @foreach( $disciplines as $discipline )
      <tr class="discipline data" key="{{ Crypt::encrypt($discipline->id) }}">
        <td>{{ $discipline->period }}</td>
        <td>{{ $discipline->name }}</td>
        <td>
          <div class="col-md-12">
            <i class="pull-right fa fa-gears icon-default click" data-toggle="dropdown" aria-expanded="false"></i>
            <ul class="dropdown-menu dropdown-menu-right" role="menu">
              <li><a class="discipline-edit"><i class="fa fa-edit text-info"></i> Editar</a></li>
              <li><a class="trash"><i class="fa fa-trash text-danger"></i> Deletar</a></li>
            </ul>
          </div>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
@else
<h4 class="text-center">O curso selecionado não possui disciplinas</h4>
@endif