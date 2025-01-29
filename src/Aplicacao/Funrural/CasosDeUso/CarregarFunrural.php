<?php
namespace Src\Aplicacao\Funrural\CasosDeUso;

use Src\Dominio\Negociacao\Entidades\Funrural;
use Src\Infraestrutura\Bd\Persistencia\FunruralRepositorio;

class CarregarFunrural
    {

    public static function executar(): bool
        {
        $repositorio = new FunruralRepositorio();
        $funrural = $repositorio->buscarTodas();
        foreach ($funrural as $f) {
            $f['aliases'] = json_decode($f['aliases'], true);
            $funruralObjeto = new Funrural(
                (int) $f["id"],
                $f["nome"],
                $f["aliases"],
            );

            Funrural::setFunruralCache($funruralObjeto);
            }
        return true;
        }
    }
