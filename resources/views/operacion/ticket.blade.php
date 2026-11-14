<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ticket N° {{ $boleto->id }}</title>

    <link rel="stylesheet" href="{{ asset('celestial/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('celestial/css/vertical-layout-light/style.css') }}">
    <style>
        body { background: #f2f4f9; }
        .ticket { max-width: 340px; margin: 30px auto; background: #fff; padding: 25px; border: 1px dashed #999; }
        .ticket td { padding: 3px 0; vertical-align: top; }
        @media print {
            body { background: #fff; }
            .no-imprimir { display: none; }
            .ticket { margin: 0 auto; border: none; }
        }
    </style>
</head>
<body>
<div class="ticket">
    <div class="text-center mb-3">
        <img src="{{ $config->logo_url }}" alt="logo" style="max-height: 60px; width: auto;">
        <h5 class="mt-2 mb-0">{{ $config->nombre_estacionamiento }}</h5>
        <small class="text-muted">Ticket digital</small>
    </div>

    <h4 class="text-center">TICKET N° {{ $boleto->id }}</h4>
    <hr>

    <table width="100%">
        <tr><td>Placa:</td><td class="text-right font-weight-bold">{{ $boleto->vehiculo->placa }}</td></tr>
        <tr><td>Tipo:</td><td class="text-right">{{ $boleto->vehiculo->tipo->nombre }}</td></tr>
        <tr><td>Espacio:</td><td class="text-right">{{ $boleto->espacio->codigo }}</td></tr>
        <tr><td>Entrada:</td><td class="text-right">{{ $boleto->hora_entrada->format('d/m/Y H:i') }}</td></tr>

        @if ($boleto->hora_salida)
            <tr><td>Salida:</td><td class="text-right">{{ $boleto->hora_salida->format('d/m/Y H:i') }}</td></tr>
            <tr><td>Tiempo:</td><td class="text-right">{{ intdiv($boleto->total_tiempo_minutos, 60) }} h {{ $boleto->total_tiempo_minutos % 60 }} min</td></tr>
            <tr><td>Método de pago:</td><td class="text-right">{{ $boleto->metodoPago->nombre }}</td></tr>
        @endif
        <tr><td>Atendido por:</td><td class="text-right">{{ $boleto->usuario->nombre }}</td></tr>
    </table>

    <hr>

    @if ($boleto->hora_salida)
        <h3 class="text-center">TOTAL: Bs {{ number_format($boleto->monto_total, 2) }}</h3>
        <p class="text-center text-success font-weight-bold mb-0">PAGADO</p>
    @else
        <p class="text-center mb-0">Presenta este ticket al salir.</p>
    @endif
</div>

<div class="text-center mb-4 no-imprimir">
    <button type="button" class="btn btn-primary" onclick="window.print()">Imprimir</button>
    <button type="button" class="btn btn-light" onclick="window.close()">Cerrar</button>
</div>
</body>
</html>
