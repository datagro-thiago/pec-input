<?php

namespace Src\Aplicacao\Frete\CasosDeUso;

use Src\Dominio\Negociacao\Entidades\Frete;
use Src\Infraestrutura\Bd\Persistencia\FreteRepositorio;

class CarregarFretes
    {
    public static function executar(): bool
        {
        $repositorio = new FreteRepositorio();
        $frete = $repositorio->buscarTodas();
        foreach ($frete as $i) {
            $freteObjeto = new Frete(
                (int) $i["id"],
                $i["industria"],
                $i["frete"],
                $i["force"]
            );
            Frete::setFreteCache($freteObjeto);
            }
        return true;
        }
    }