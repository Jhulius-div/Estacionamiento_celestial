<?php

namespace App\Support;

class Tarifa
{
    // Minutos entre la entrada y la salida
    public static function minutos($entrada, $salida): int
    {
        return intdiv($salida->getTimestamp() - $entrada->getTimestamp(), 60);
    }

    // Se cobra por hora o fracción (mínimo 1 hora)
    public static function calcular(int $minutos, float $tarifaHora): float
    {
        $horas = max(1, (int) ceil($minutos / 60));

        return round($horas * $tarifaHora, 2);
    }
}
