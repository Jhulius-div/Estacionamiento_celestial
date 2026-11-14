@foreach ($vehiculos as $v)
    <tr>
        <td>{{ $v->id }}</td>
        <td>{{ $v->placa }}</td>
        <td>{{ $v->tipo->nombre }}</td>
        <td>{{ $v->marca }}</td>
        <td>{{ $v->modelo }}</td>
        <td>{{ $v->color }}</td>
        <td>{{ $v->cliente->nombre ?? '-' }}</td>
        <td>
            @if ($v->estado)
                <span class="badge badge-success">Activo</span>
            @else
                <span class="badge badge-danger">Inactivo</span>
            @endif
        </td>
        <td>
            <button type="button" class="btn btn-warning btn-sm btn-editar"
                    data-json="{{ json_encode($v->only(['id', 'placa', 'id_tipo_vehiculo', 'id_cliente', 'marca', 'modelo', 'color'])) }}">Editar</button>
            <button type="button" class="btn btn-sm btn-estado {{ $v->estado ? 'btn-danger' : 'btn-success' }}"
                    data-id="{{ $v->id }}" data-accion="{{ $v->estado ? 'Desactivar' : 'Activar' }}">{{ $v->estado ? 'Desactivar' : 'Activar' }}</button>
        </td>
    </tr>
@endforeach
