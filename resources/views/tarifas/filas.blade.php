@foreach ($tipos as $t)
    <tr>
        <td>{{ $t->id }}</td>
        <td>{{ $t->nombre }}</td>
        <td>Bs {{ number_format($t->tarifa_hora, 2) }}</td>
        <td>
            <button type="button" class="btn btn-warning btn-sm btn-editar"
                    data-json="{{ json_encode($t->only(['id', 'nombre', 'tarifa_hora'])) }}">Editar</button>
        </td>
    </tr>
@endforeach
