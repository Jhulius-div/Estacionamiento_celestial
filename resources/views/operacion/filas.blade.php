@foreach ($boletos as $b)
    <tr>
        <td>{{ $b->id }}</td>
        <td>{{ $b->vehiculo->placa }}</td>
        <td>{{ $b->vehiculo->tipo->nombre }}</td>
        <td>{{ $b->espacio->codigo }}</td>
        <td>{{ $b->hora_entrada->format('d/m H:i') }}</td>
        <td>{{ intdiv(App\Support\Tarifa::minutos($b->hora_entrada, now()), 60) }} h {{ App\Support\Tarifa::minutos($b->hora_entrada, now()) % 60 }} min</td>
        <td>
            <button type="button" class="btn btn-primary btn-sm btn-cobrar" data-id="{{ $b->id }}">Cobrar salida</button>
            <a href="{{ route('operacion.ticket', $b->id) }}" target="_blank" class="btn btn-light btn-sm">Ticket</a>
        </td>
    </tr>
@endforeach
