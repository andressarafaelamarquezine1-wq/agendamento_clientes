<?php

function calcularDataRota(int $ano, int $mes, int $diaFixo, string $regra = 'PROXIMA_SEGUNDA'): DateTimeImmutable
{
    $primeiroDiaMes = new DateTimeImmutable(sprintf('%04d-%02d-01', $ano, $mes));
    $ultimoDiaDoMes = (int) $primeiroDiaMes->format('t');
    $diaValido = min($diaFixo, $ultimoDiaDoMes);

    $data = $primeiroDiaMes->setDate($ano, $mes, $diaValido);
    $diaSemana = (int) $data->format('N'); // 1 (Seg) a 7 (Dom)

    if ($regra === 'PROXIMA_SEGUNDA') {
        if ($diaSemana === 6) { // Sábado
            $data = $data->modify('+2 days');
        } elseif ($diaSemana === 7) { // Domingo
            $data = $data->modify('+1 day');
        }
    } elseif ($regra === 'SEXTA_ANTERIOR') {
        if ($diaSemana === 6) { // Sábado
            $data = $data->modify('-1 day');
        } elseif ($diaSemana === 7) { // Domingo
            $data = $data->modify('-2 days');
        }
    }

    return $data;
}