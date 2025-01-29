<?php

namespace Src\Dominio\Negociacao\Servicos;

use Src\Dominio\Negociacao\Entidades\PrecoCDI;

class ServicoCdi
    {
    public static function buscarCdi(): ?float
        {
        $resultado = PrecoCDI::buscarUltDi();
        return (float) $resultado;
        }
    }