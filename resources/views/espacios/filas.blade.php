@foreach ($espacios as $e)
    <tr>
        <td>{{ $e->id }}</td>
        <td>{{ $e->codigo }}</td>
        <td>{{ $e->piso }}</td>
        <td>{{ $e->sector }}</td>
        <td>{{ $e->numero }}</td>
        <td>
            @if ($e->esta_ocupado)
                <span class="badge badge-danger">Ocupado</span>
            @else
                <span class="badge badge-success">Libre</span>
            @endif
        </td>
        <td>
            @if ($e->estado)
                <span class="badge badge-success">Activo</span>
            @else
                <span class="badge badge-danger">Inactivo</span>
            @endif
        </td>
        @if (auth()->user()->tieneRol('Administrador'))
            <td>
                <button type="button" class="btn btn-warning btn-sm btn-editar"
                        data-json="{{ json_encode($e->only(['id', 'piso', 'sector', 'numero'])) }}">Editar</button>
                <button type="button" class="btn btn-sm btn-estado {{ $e->estado ? 'btn-danger' : 'btn-success' }}"
                        data-id="{{ $e->id }}" data-accion="{{ $e->estado ? 'Desactivar' : 'Activar' }}">{{ $e->estado ? 'Desactivar' : 'Activar' }}</button>
            </td>
        @endif
    </tr>
@endforeach
