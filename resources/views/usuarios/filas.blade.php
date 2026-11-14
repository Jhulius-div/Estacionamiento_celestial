@foreach ($usuarios as $u)
    <tr>
        <td>{{ $u->id }}</td>
        <td>{{ $u->nombre }}</td>
        <td>{{ $u->correo }}</td>
        <td>{{ $u->rol->nombre }}</td>
        <td>
            @if ($u->estado)
                <span class="badge badge-success">Activo</span>
            @else
                <span class="badge badge-danger">Inactivo</span>
            @endif
        </td>
        <td>
            <button type="button" class="btn btn-warning btn-sm btn-editar"
                    data-json="{{ json_encode($u->only(['id', 'nombre', 'correo', 'id_rol'])) }}">Editar</button>
            <button type="button" class="btn btn-sm btn-estado {{ $u->estado ? 'btn-danger' : 'btn-success' }}"
                    data-id="{{ $u->id }}" data-accion="{{ $u->estado ? 'Desactivar' : 'Activar' }}">{{ $u->estado ? 'Desactivar' : 'Activar' }}</button>
        </td>
    </tr>
@endforeach
