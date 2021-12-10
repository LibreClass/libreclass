@if(count($periods) > 0)
<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th>{{ ucfirst(strtolower(session('period.singular'))) }}</th>
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
                        <li><a class="open-modal-remove-period" data-id="{{$period->id}}"><i class="fa fa-trash text-danger"></i> Remover</a></li>
                    </ul>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
    <span>O curso selecionado não possui {{ strtolower(session('period.plural')) }} cadastrad{{ strtolower(session('period.article')) == 'a' ? 'a':'o' }}s.</span>
@endif