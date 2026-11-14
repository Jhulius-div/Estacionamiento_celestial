@foreach ($espacios->groupBy('piso') as $piso => $lista)
    <h6 class="mt-3">Piso {{ $piso }}</h6>
    <div class="d-flex flex-wrap">
        @foreach ($lista as $e)
            @if (! $e->estado)
                <span class="badge badge-secondary mr-2 mb-2 p-2" style="min-width: 70px;">{{ $e->sector }}-{{ $e->numero }}</span>
            @elseif ($e->esta_ocupado)
                <span class="badge badge-danger mr-2 mb-2 p-2" style="min-width: 70px;">{{ $e->sector }}-{{ $e->numero }}</span>
            @else
                <span class="badge badge-success mr-2 mb-2 p-2" style="min-width: 70px;">{{ $e->sector }}-{{ $e->numero }}</span>
            @endif
        @endforeach
    </div>
@endforeach
