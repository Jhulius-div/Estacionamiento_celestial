@foreach ($clientes as $c)
    <tr>
        <td>{{ $c->id }}</td>
        <td>{{ $c->nombre }}</td>
        <td>{{ $c->telefono }}</td>
        <td>{{ $c->correo }}</td>
        <td>
            @if ($c->es_suscriptor)
                <span class="badge badge-info">Sí</span>
            @else
                <span class="badge badge-light">No</span>
            @endif
        </td>
        <td>{{ $c->fecha_vencimiento ? date('d/m/Y', strtotime($c->fecha_vencimiento)) : '-' }}</td>
        <td>
            @if ($c->estado)
                <span class="badge badge-success">Activo</span>
            @else
                <span class="badge badge-danger">Inactivo</span>
            @endif
        </td>
        <td>
            <button type="button" class="btn btn-warning btn-sm btn-editar"
                    data-json="{{ json_encode($c->only(['id', 'nombre', 'telefono', 'correo', 'es_suscriptor', 'fecha_vencimiento'])) }}">Editar</button>
            <button type="button" class="btn btn-sm btn-estado {{ $c->estado ? 'btn-danger' : 'btn-success' }}"
                    data-id="{{ $c->id }}" data-accion="{{ $c->estado ? 'Desactivar' : 'Activar' }}">{{ $c->estado ? 'Desactivar' : 'Activar' }}</button>
        </td>
    </tr>
@endforeach
